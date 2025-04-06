<template>
  <div class="min-h-screen bg-gray-50 text-gray-800 font-sans">
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-sm">
      <div class="text-xl font-semibold flex items-center gap-2">
        modzee
      </div>
      <div class="flex gap-4 items-center">
        <button class="text-gray-500 hover:text-gray-700">
          🔔
        </button>
        <button class="w-8 h-8 rounded-full bg-gray-300"></button>
      </div>
    </header>

    <main class="p-6 grid md:grid-cols-2 gap-6 max-w-6xl mx-auto">
      <div class="bg-white p-6 rounded-2xl shadow">
        <h2 class="text-lg font-semibold mb-4">Chat Assistant</h2>
        <div class="space-y-3 mb-4">
          <div class="bg-blue-500 text-white px-4 py-2 rounded-xl inline-block w-fit">
            Summarize team performance for March.
          </div>
          <div class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl">
            In March, the team demonstrated strong performance, achieving key project milestones and exceeding targets...
          </div>
        </div>
        <div class="flex items-center border rounded-xl px-3 py-2">
          <input
              v-model="prompt"
              type="text"
              placeholder="Type your message here..."
              class="w-full outline-none"
          />
          <button class="ml-2 text-blue-500 hover:text-blue-700 text-xl">📤</button>
        </div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow">
        <h2 class="text-lg font-semibold mb-4">Reporting Assistant</h2>
        <textarea
            v-model="jsonData"
            rows="4"
            placeholder="Paste JSON data here..."
            class="w-full border rounded-xl p-3 mb-4 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-300 resize-none"
        ></textarea>

        <h3 class="font-medium text-sm text-gray-600 mb-1">Summary</h3>
        <div class="bg-gray-100 text-gray-700 p-4 rounded-xl mb-4">
          {{ report }}
        </div>

        <button
            @click="generateReport"
            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-xl w-full"
        >
          Generate Report
        </button>
      </div>
    </main>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      prompt: "",
      jsonData: "",
      report:
          "The report indicates a concerning decline in engagement scores and attendance rates, particularly for Sales Employee 3...",
    };
  },
  methods: {
    async generateReport() {
      let payload = {};
      if (this.jsonData.trim()) {
        try {
          payload.data = JSON.parse(this.jsonData);
        } catch (e) {
          alert("Invalid JSON format!");
          return;
        }
      }

      const res = await axios.post("http://localhost:8000/api/ai/report", payload);
      this.report = res.data.summary;
    },
  },
};
</script>
