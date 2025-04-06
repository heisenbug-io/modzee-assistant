<template>
  <div class="bg-white p-6 rounded-2xl shadow flex flex-col h-[calc(100vh-5rem)]">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-semibold">Chat Assistant</h2>
      <button @click="resetConversation" class="btn text-red-500 hover:underline">
        Reset
      </button>
    </div>

    <div class="flex-1 min-h-0 overflow-y-auto mb-4 space-y-3 pr-2">
      <div ref="scrollContainer" class="overflow-y-auto h-full space-y-3 pr-2">
        <div v-for="(entry, index) in conversation" :key="index" class="space-y-1">
          <!-- User message -->
          <div
              v-if="entry.role === 'user'"
              class="bg-blue-500 text-white px-4 py-2 rounded-xl w-fit ml-auto text-sm"
          >
            <div>{{ entry.text }}</div>
            <div class="text-xs text-blue-200 mt-1 text-right">{{ entry.timestamp }}</div>
          </div>

          <!-- AI message -->
          <div
              v-else
              class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl w-fit max-w-full text-sm"
          >
            <div
                class="prose prose-sm"
                v-html="parseMarkdown(entry.text)"
            ></div>

            <div>{{ entry.text }}</div>
            <div class="text-xs text-gray-400 mt-1 text-left">{{ entry.timestamp }}</div>
          </div>
        </div>
      </div>
    </div>


    <form @submit.prevent="submitPrompt" class="flex items-center border rounded-xl px-3 py-2">
      <textarea
          ref="textarea"
          v-model="prompt"
          placeholder="Type your message here..."
          rows="1"
          class="w-full resize-none outline-none py-1 max-h-48 overflow-auto"
          @input="autoResize"
          @keydown.enter.exact.prevent="submitPrompt"
      />
      <button :disabled="loading" class="ml-2 text-blue-500 hover:text-blue-700 text-xl">Send 📤</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import { marked } from 'marked';

export default {
  name: 'ChatAssistant',
  data() {
    return {
      prompt: '',
      conversation: [],
      loading: false,
    };
  },
  created() {
    this.fetchConversation();
  },
  methods: {
    async submitPrompt() {
      if (!this.prompt.trim()) return;

      const input = this.prompt;
      this.prompt = '';
      this.loading = true;

      try {
        const response = await axios.post('http://localhost:8000/api/ai/assistant', {
          prompt: input,
        });

        this.conversation.push({ role: 'user', text: input });
        this.conversation.push({ role: 'model', text: response.data.reply });
      } catch (error) {
        alert(error.response?.data?.error || 'Error occurred.');
      } finally {
        this.loading = false;
        this.scrollToBottom();
      }
    },
    parseMarkdown(text) {
      return marked.parse(text);
    },
    autoResize() {
      const el = this.$refs.textarea;
      if (!el) return;
      el.style.height = 'auto';
      el.style.height = `${el.scrollHeight}px`;
    },
    async fetchConversation() {
      try {
        const res = await axios.get('http://localhost:8000/api/ai/logs');
        this.conversation = res.data;
        this.scrollToBottom();
      } catch (err) {
        console.error('Failed to fetch logs', err);
      }
    },
    async resetConversation() {
      try {
        await axios.post('http://localhost:8000/api/ai/reset');
        this.conversation = [];
        this.scrollToBottom();
      } catch {
        alert('Failed to reset.');
      }
    },
    scrollToBottom() {
      this.$nextTick(() => {
        const container = this.$refs.scrollContainer;
        if (container) {
          container.scrollTo({
            top: container.scrollHeight,
            behavior: 'smooth',
          });
        }
      });
    },
  }
};
</script>
