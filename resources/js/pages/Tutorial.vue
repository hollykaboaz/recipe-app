<script setup>

</script>

<template>
    <div class="bg-white rounded-lg px-8 py-4">
        <slot></slot>
        <h2 class="font-semibold text-xl mb-4">How to Use the App 🤗</h2>
        <ol class="list-disc list-inside flex flex-col gap-3">
            <li><span class="mr-2">🔍</span> <b> Paste the URL of the Youtube Recipe </b> into the input box above</li>
            <li ><span class="mr-2">🤖</span> <b> Give AI time to work it's magic  </b> and extract the recipe steps (NOTE: this could take up to 1-2 minutes depending on the length of the tutorial)</li>
            <li><span class="mr-2">👩</span> Happy Cooking! <b> Enjoy in sync steps and ingredient lists </b> synced to the tutorial! No more BS Recipes!</li>
        </ol>
        <p class="mt-8 text-sm text-gray-500">Sometimes AI gets things wrong (and that's expected!) Let me know what mistakes are made and I can continue to make this a better app. Send a message to aleahholly@gmail.com</p>
    </div>
</template>

<style scoped>

</style>