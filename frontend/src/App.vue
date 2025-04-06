<template>
  <div class="min-h-screen bg-gray-50 text-gray-800 font-sans">
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-sm">
      <div class="text-xl font-semibold flex items-center gap-2">
        <span class="text-blue-600">modzee</span> AI Assistant
      </div>
    </header>

    <main class="p-6 grid gap-6 lg:grid-cols-2 max-w-8xl mx-auto">
      <ChatAssistant />
      <ReportAssistant />
    </main>
  </div>
</template>

<script>
import ChatAssistant from './components/ChatAssistant.vue';
import ReportAssistant from './components/ReportAssistant.vue';

export default {
  components: {
    ChatAssistant,
    ReportAssistant,
  },
};
</script>
