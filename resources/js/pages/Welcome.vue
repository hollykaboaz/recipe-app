<script setup lang="js">
import { onMounted, reactive } from 'vue';

onMounted(() => {
    feather.replace();
});

const props = defineProps({
    recipe: Object,
    ingredients: Array,
    videoId: String,
});

const state = reactive({
    activeStepIndex: 0,
    start: props.recipe[0]?.timestamp_seconds || 0,
});

function updateActiveStep(step, stepIndex) {
    state.activeStepIndex = stepIndex;
    state.start = step.timestamp_seconds;
}
</script>

<template>
    <div class="max-h-screen overflow-clip bg-lightPeach bg-[url('/wave.svg')] bg-cover bg-center bg-no-repeat">
        <div class="mx-auto max-w-7xl px-6 pt-8 pb-20">
            <div class="mb-8 flex flex-row">
                <!-- Recipe Link -->
                <div class="flex flex-col basis-1/2 gap-0.5">
                    <span class="text-sm text-[#DD8F47] font-bold"> skip the bs</span>
                    <h1 class="text-[#DD8F47] text-3xl font-black">
                        JustCook
                    </h1>
                </div>


                <div class="flex basis-1/2 flex-row items-center gap-5 rounded-lg bg-white p-2">
                    <i class="text-gray-400" data-feather="search"></i>
                    <input
                        type="text"
                        class="w-full rounded-md text-gray-400 placeholder:text-gray-300 focus:outline-none"
                        placeholder="What are you making today?"
                    />
                </div>
            </div>

            <div class="flex flex-row gap-16">
                <!-- Ingredients List -->
                <div class="flex basis-2/6 flex-col">
                    <div class="text-xl font-bold text-[#DD8F47] mb-4">
                        <h2>Ingredients</h2>
                    </div>

                    <div class="flex flex-col gap-2 max-h-screen overflow-y-scroll no-scrollbar">
                        <div v-for="ingredient in ingredients" class="px-6 bg-white rounded-lg flex flex-row justify-between py-1">
                            <div class="flex flex-col gap-0.5 rounded-md p-4">
                                <div class="text-sm text-[#DD8F47] uppercase font-bold">2 cups</div>
                                <!--                            <div v-if="ingredient.measurement" class="text-sm uppercase font-medium text-[#DD8F47]">-->
                                <!--                                {{ ingredient.measurement }}-->
                                <!--                            </div> -->

                                <div class="text-md font-bold capitalize">
                                    {{ ingredient }}
                                </div>
                                <!--                            <div v-if="ingredient.calories" class="text-gray-500 text-sm">-->
                                <!--                                {{ ingredient.calories }}-->
                                <!--                            </div>-->
                                <div class="text-sm text-gray-500">16 calories</div>
                            </div>

                            <i data-feather="check-circle" class="text-[#DD8F47] self-center"></i>
                        </div>
                    </div>
                </div>

                <!-- Recipe Video & Steps -->
                <div class="flex basis-4/6 flex-col gap-8">
                    <iframe
                        class="grid h-[360px] content-center items-center rounded-lg"
                        id="ytplayer"
                        type="text/html"
                        :src="
                            'https://www.youtube.com/embed/' +
                            videoId +
                            '?autoplay=1' +
                            (state.start ? '&start=' + state.start : '') +
                            (state.end ? '&end=' + state.end : '')
                        "
                        frameborder="0"
                    ></iframe>

                    <div class="no-scrollbar flex max-h-screen flex-col gap-8 overflow-y-scroll">
                        <div
                            v-for="(step, stepIndex) in recipe"
                            :key="stepIndex"
                            @click="updateActiveStep(step, stepIndex)"
                            :class="stepIndex !== state.activeStepIndex ? 'opacity-30 transition-all hover:opacity-80' : 'shadow-sm'"
                            class="flex flex-row items-start gap-x-6 rounded-lg bg-white px-3 py-4 transition-opacity duration-[2000ms] hover:cursor-pointer"
                        >
                            <div class="grow-0 text-sm text-[#8D8A81]">
                                {{ step.timestamp }}
                            </div>
                            <div class="flex grow-1 flex-col gap-1.5">
                                <h3 class="text-xl font-bold capitalize">
                                    {{ step.title }}
                                </h3>
                                <div class="flex flex-row flex-wrap gap-1">
                                    <div v-for="ingredient in step.ingredients" class="rounded-md bg-[#FFE8C7] px-2 py-1 text-sm text-[#DD8F47]">
                                        {{ ingredient }}
                                    </div>
                                </div>
                                <p class="mt-1.5 text-[#8D8A81]">
                                    {{ step.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
