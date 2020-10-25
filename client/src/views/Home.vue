<template>
  <div class="home">
    <img alt="Vue logo" src="../assets/logo.png" />
    <HelloWorld msg="Welcome to Your Vue.js App" />
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import HelloWorld from "@/components/Home/HelloWorld.vue";

export default defineComponent({
  name: "Home",
  components: {
    HelloWorld,
  },
});
</script>
