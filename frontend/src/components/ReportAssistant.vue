<template>
  <div class="bg-white p-6 rounded-2xl shadow flex flex-col h-full">
    <h2 class="text-lg font-semibold mb-4">Reporting Assistant</h2>

    <textarea
        v-model="jsonData"
        rows="6"
        placeholder="Paste JSON data here (optional)..."
        class="w-full border rounded-xl p-3 mb-4 bg-gray-50 resize-none focus:outline-none focus:ring-2 focus:ring-blue-300"
    ></textarea>

    <h3 class="font-medium text-sm text-gray-600 mb-1">Summary</h3>
    <div class="bg-gray-100 text-gray-700 p-4 rounded-xl mb-4 whitespace-pre-wrap">
      {{ report }}
    </div>

    <button
        @click="generateReport"
        :disabled="loading"
        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-xl w-full"
    >
      {{ loading ? "Generating..." : "Generate Report" }}
    </button>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ReportAssistant',
  data() {
    return {
      jsonData: '',
      report: '',
      loading: false,
    };
  },
  methods: {
    async generateReport() {
      let payload = {};
      if (this.jsonData.trim()) {
        try {
          payload.data = JSON.parse(this.jsonData);
        } catch (e) {
          alert("Invalid JSON format.");
          return;
        }
      }

      this.loading = true;
      try {
        const response = await axios.post('http://localhost:8000/api/ai/report', payload);
        this.report = response.data.summary;
      } catch (error) {
        this.report = error.response?.data?.error || 'Failed to generate report.';
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>
