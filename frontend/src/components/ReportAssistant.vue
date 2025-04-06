<template>
  <div>
    <h2>Generate Report</h2>
    <textarea
        v-model="inputData"
        placeholder="Optional: Enter JSON data to generate report (leave empty to use default dataset)"
        rows="10"
        cols="50"
    ></textarea>
    <br />
    <button @click="generateReport" :disabled="loading">
      Generate Report
    </button>
    <div v-if="loading">Generating report...</div>
    <div v-if="error" class="error">{{ error }}</div>
    <div v-if="report">
      <h3>Report Summary:</h3>
      <p>{{ report.summary }}</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ReportAssistant',
  data() {
    return {
      inputData: '',
      report: null,
      loading: false,
      error: null,
    };
  },
  methods: {
    async generateReport() {
      this.loading = true;
      this.error = null;
      this.report = null;

      let payload = {};
      // If inputData is provided, try to parse it as JSON.
      if (this.inputData.trim()) {
        try {
          payload.data = JSON.parse(this.inputData);
        } catch (e) {
          this.error = "Invalid JSON data.";
          this.loading = false;
          return;
        }
      }
      try {
        // Adjust the API URL if needed; here we assume the backend runs on port 8000.
        const res = await axios.post('http://127.0.0.1:8000/api/ai/report', payload);
        this.report = res.data;
      } catch (err) {
        this.error =
            err.response?.data?.error ||
            'An error occurred while generating the report.';
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
