<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Friendship;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $conversations = Conversation::with(['userOne', 'userTwo'])
            ->forUser($user->id)
            ->orderByDesc('last_message_at')
            ->get();

        return view('chat.index', compact('conversations', 'user'));
    }

    public function show(User $user)
    {
        $me = Auth::user();
        abort_unless(Friendship::areFriends($me->id, $user->id) || $me->id === $user->id, 403);

        $conversation = Conversation::between($me->id, $user->id);
        $conversation->load('messages.sender');

        return view('chat.show', [
            'conversation' => $conversation,
            'me' => $me,
            'other' => $user,
        ]);
    }

    public function messages(User $user, Request $request)
    {
        $me = Auth::user();
        abort_unless(Friendship::areFriends($me->id, $user->id) || $me->id === $user->id, 403);

        $conversation = Conversation::between($me->id, $user->id);

        $since = $request->query('since');
        $query = $conversation->messages()->with('sender');
        if ($since) {
            $query->where('created_at', '>', $since);
        }
        $messages = $query->get()->map(function ($m) use ($me) {
            return [
                'id' => $m->id,
                'sender_id' => $m->sender_id,
                'me' => $m->sender_id === $me->id,
                'body' => $m->body,
                'image_url' => $m->image_url,
                'created_at' => $m->created_at->toIso8601String(),
                'sender_name' => $m->sender->name,
                'sender_avatar' => $m->sender->profile_picture ? Storage::url($m->sender->profile_picture) : null,
            ];
        });

        return response()->json([ 'messages' => $messages ]);
    }

    public function send(User $user, Request $request)
    {
        $me = Auth::user();
        abort_unless(Friendship::areFriends($me->id, $user->id) || $me->id === $user->id, 403);

        $validated = $request->validate([
            'body' => 'nullable|string|max:5000',
            'image' => 'nullable|image|max:4096',
        ]);

        if (empty($validated['body']) && !$request->hasFile('image')) {
            return back();
        }

        $conversation = Conversation::between($me->id, $user->id);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $me->id,
            'body' => $validated['body'] ?? null,
            'image_path' => $imagePath,
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'id' => $message->id]);
        }

        return back();
    }

    // Server-Sent Events stream for real-time updates without external services
    public function stream(User $user, Request $request)
    {
        $me = Auth::user();
        abort_unless(Friendship::areFriends($me->id, $user->id) || $me->id === $user->id, 403);

        $conversation = Conversation::between($me->id, $user->id);
        $since = $request->query('since');
        $sinceTime = $since ? now()->parse($since) : now();

    return response()->stream(function () use ($conversation, $me, &$sinceTime) {
            @ini_set('zlib.output_compression', '0');
            @ini_set('output_buffering', 'off');
            @ini_set('implicit_flush', '1');
            ignore_user_abort(true);

            $start = time();
            while (!connection_aborted() && (time() - $start) < 120) { // keep open ~120s
                $new = $conversation->messages()->withTrashed()->with('sender')
                    ->where(function($q) use ($sinceTime) {
                        $q->where('created_at', '>', $sinceTime)
                          ->orWhere('updated_at', '>', $sinceTime)
                          ->orWhere(function($qq) use ($sinceTime){ $qq->whereNotNull('deleted_at')->where('deleted_at', '>', $sinceTime); });
                    })
                    ->orderBy('updated_at')
                    ->limit(100)
                    ->get();

                if ($new->isNotEmpty()) {
                    foreach ($new as $m) {
                        $payload = [
                            'id' => $m->id,
                            'sender_id' => $m->sender_id,
                            'me' => $m->sender_id === $me->id,
                            'body' => $m->body,
                            'image_url' => $m->image_url,
                            'created_at' => $m->created_at->toIso8601String(),
                            'sender_name' => $m->sender->name,
                            'deleted' => $m->trashed(),
                            'updated_at' => $m->updated_at?->toIso8601String(),
                        ];
                        echo "event: message\n";
                        echo 'data: ' . json_encode($payload) . "\n\n";
                        $sinceTime = max($sinceTime, $m->updated_at ?? $m->created_at, $m->deleted_at ?? $m->created_at);
                    }
                } else {
                    // keepalive
                    echo ": ping\n\n";
                }
                @ob_flush();
                @flush();
                sleep(2);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection' => 'keep-alive',
        ]);
    }

    public function updateMessage(User $user, Message $message, Request $request)
    {
        $me = Auth::user();
        abort_unless($message->sender_id === $me->id, 403);
        $conv = Conversation::between($me->id, $user->id);
        abort_unless($message->conversation_id === $conv->id, 403);
        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);
        $message->update(['body' => $validated['body']]);
        return response()->json(['ok' => true]);
    }

    public function deleteMessage(User $user, Message $message)
    {
        $me = Auth::user();
        abort_unless($message->sender_id === $me->id, 403);
        $conv = Conversation::between($me->id, $user->id);
        abort_unless($message->conversation_id === $conv->id, 403);
        $message->delete();
        return response()->json(['ok' => true]);
    }
}
