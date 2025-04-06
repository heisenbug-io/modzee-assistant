<template>
  <div class="bg-white p-6 rounded-2xl shadow flex flex-col h-full">
    <h2 class="text-lg font-semibold mb-4">Chat Assistant</h2>

    <div class="space-y-3 flex-1 overflow-y-auto mb-4">
      <div
          v-for="(entry, index) in conversation"
          :key="index"
          class="space-y-1"
      >
        <div class="bg-blue-500 text-white px-4 py-2 rounded-xl w-fit ml-auto">
          {{ entry.prompt }}
        </div>
        <div class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl w-fit max-w-full">
          {{ entry.response }}
        </div>
      </div>
    </div>

    <form @submit.prevent="submitPrompt" class="flex items-center border rounded-xl px-3 py-2">
      <input
          v-model="prompt"
          type="text"
          placeholder="Type your message here..."
          class="w-full outline-none"
      />
      <button :disabled="loading" class="ml-2 text-blue-500 hover:text-blue-700 text-xl">📤</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ChatAssistant',
  data() {
    return {
      prompt: '',
      conversation: [],
      loading: false,
    };
  },
  methods: {
    async submitPrompt() {
      if (!this.prompt.trim()) return;

      const promptText = this.prompt;
      this.prompt = '';
      this.loading = true;

      try {
        const response = await axios.post('http://localhost:8000/api/ai/assistant', {
          prompt: promptText,
        });

        this.conversation.push({
          prompt: promptText,
          response: response.data.reply,
        });
      } catch (error) {
        alert(error.response?.data?.error || 'Something went wrong.');
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
/* Optional: Add scroll behavior */
</style>
