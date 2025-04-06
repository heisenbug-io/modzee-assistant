<template>
  <div class="min-h-screen bg-gray-50 text-gray-800 font-sans">
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-sm">
      <div class="text-xl font-semibold flex items-center gap-2">
        <span class="text-blue-600">modzee</span> AI Assistant
      </div>
    </header>

    <main class="p-6 grid gap-6 lg:grid-cols-1 max-w-7xl mx-auto">
      <ChatAssistant />

    </main>
  </div>
</template>

<script>
import ChatAssistant from './components/ChatAssistant.vue';

export default {
  components: {
    ChatAssistant,
  },
};
</script>
