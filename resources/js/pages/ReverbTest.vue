<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import { useEcho } from '@laravel/echo-vue';

interface Message {
    sender: string;
    message: string;
    time: string;
}

const messages = ref<Message[]>([]);
const form = useForm({
    message: '',
    sender: 'User-' + Math.random().toString(36).substring(2, 6),
});

const { listen, stopListening, leaveChannel } = useEcho('chat');

onMounted(() => {
    console.log('Listening on channel: chat');

    listen('.ChatMessage', (event: Message) => {
        console.log('Received message:', event);
        messages.value.push(event);
    });
});

onUnmounted(() => {
    stopListening('.ChatMessage');
    leaveChannel();
});

function sendMessage() {
    if (!form.message.trim()) return;

    form.post('/reverb-test/send', {
        preserveScroll: true,
        onSuccess: () => {
            form.message = '';
        },
    });
}
</script>

<template>
    <Head title="Reverb Test" />

    <div class="min-h-screen bg-gray-100 p-8 dark:bg-gray-900">
        <div class="mx-auto max-w-2xl">
            <h1 class="mb-6 text-3xl font-bold text-gray-900 dark:text-white">
                Reverb WebSocket Test
            </h1>

            <div class="mb-6 rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                <h2
                    class="mb-2 text-lg font-semibold text-gray-900 dark:text-white"
                >
                    Instructions
                </h2>
                <ol
                    class="list-inside list-decimal space-y-1 text-gray-600 dark:text-gray-300"
                >
                    <li>Open this page in two browser tabs/windows</li>
                    <li>Send a message from one tab</li>
                    <li>
                        See the message appear in both tabs (check console too)
                    </li>
                </ol>
            </div>

            <div class="mb-6 rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                <h2
                    class="mb-4 text-lg font-semibold text-gray-900 dark:text-white"
                >
                    Send Message
                </h2>
                <form @submit.prevent="sendMessage" class="flex gap-2">
                    <input
                        v-model="form.sender"
                        type="text"
                        placeholder="Your name"
                        class="w-32 rounded border border-gray-300 px-3 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                    <input
                        v-model="form.message"
                        type="text"
                        placeholder="Type a message..."
                        class="flex-1 rounded border border-gray-300 px-3 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        @keyup.enter="sendMessage"
                    />
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 disabled:opacity-50"
                    >
                        Send
                    </button>
                </form>
            </div>

            <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                <h2
                    class="mb-4 text-lg font-semibold text-gray-900 dark:text-white"
                >
                    Messages ({{ messages.length }})
                </h2>
                <div
                    v-if="messages.length === 0"
                    class="text-gray-500 dark:text-gray-400"
                >
                    No messages yet. Send one or open another tab!
                </div>
                <div v-else class="space-y-2">
                    <div
                        v-for="(msg, index) in messages"
                        :key="index"
                        class="rounded bg-gray-100 p-3 dark:bg-gray-700"
                    >
                        <div class="flex justify-between">
                            <span
                                class="font-semibold text-blue-600 dark:text-blue-400"
                                >{{ msg.sender }}</span
                            >
                            <span
                                class="text-sm text-gray-500 dark:text-gray-400"
                                >{{ msg.time }}</span
                            >
                        </div>
                        <p class="text-gray-800 dark:text-gray-200">
                            {{ msg.message }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="mt-4 text-sm text-gray-500 dark:text-gray-400"
            >
                Your ID: <code class="rounded bg-gray-200 px-1 dark:bg-gray-700">{{ form.sender }}</code>
                | Channel: <code class="rounded bg-gray-200 px-1 dark:bg-gray-700">chat</code>
            </div>
        </div>
    </div>
</template>
