<template>
  <div>
    <h2>AI Assistant</h2>
    <form @submit.prevent="submitPrompt">
      <input v-model="prompt" placeholder="Enter your prompt" />
      <button type="submit" :disabled="loading">Submit</button>
    </form>
    <div v-if="loading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>
    <div v-if="response">
      <h3>AI Response:</h3>
      <p>{{ response.reply }}</p>
      <small>{{ response.timestamp }}</small>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AiAssistant',
  data() {
    return {
      prompt: '',
      response: null,
      loading: false,
      error: null,
    };
  },
  methods: {
    async submitPrompt() {
      this.loading = true;
      this.error = null;
      try {
        // Adjust the API URL as needed. Assuming backend is on port 8000.
        const res = await axios.post('http://127.0.0.1:8000/api/ai/assistant', { prompt: this.prompt });
        this.response = res.data;
        this.prompt = '';
      } catch (err) {
        this.error = err.response?.data?.error || 'An error occurred';
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
.error {
  color: red;
}
</style>
