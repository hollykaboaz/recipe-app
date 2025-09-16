<script setup lang="js">
import feather from 'feather-icons';
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
    <div class="max-h-screen overflow-clip bg-[white] bg-[url('/wave.svg')] bg-cover bg-center bg-no-repeat">
        <div class="mx-auto max-w-7xl px-6 pt-8">
            <div class="flex flex-row gap-16">
                <!-- Logo -->
                <div class="flex basis-2/6 flex-col">
                    <div class="mb-8 flex flex-col leading-tight">
                        <div class="text-3xl font-black text-[#DD8F47]">
                            Just
                            <span class="text-[#ab9b8d]"> Cook</span>
                            <div class="inline-flex relative top-1">
                                🍳
                            </div>
                        </div>
                        <span class="text-sm font-bold text-[#DD8F47]/50 lowercase"> Straight Dish, No Garnish</span>
                    </div>

                    <!-- Ingredients List -->
                    <div class="mb-4 text-xl font-bold text-[#DD8F47]">
                        <h2>Recipe Ingredients</h2>
                    </div>

                    <div class="no-scrollbar flex max-h-[510px] flex-col gap-2 overflow-y-scroll">
                        <div v-for="ingredient in ingredients" class="flex flex-row justify-between rounded-lg bg-[#FFF9EF] px-6 py-1">
                            <div class="flex flex-col gap-0.5 rounded-md p-4">
                                <div class="text-sm font-bold text-[#DD8F47] uppercase">2 cups</div>
                                <!--                            <div v-if="ingredient.measurement" class="text-sm uppercase font-bold text-[#DD8F47]">-->
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

                            <i data-feather="check-circle" class="self-center text-[#DD8F47]"></i>
                        </div>
                    </div>

                    <div class="justify-between flex flex-row py-3">
                        <p class="text-sm font-black text-[#DD8F47]/50">Est. servings: 4</p>
                        <p class="text-sm font-black text-[#DD8F47]/50">Est. calories: 430kcal</p>
                    </div>

                    <div>
                        <button class="mt-3 w-full rounded-lg bg-[#DD8F47] px-4 py-2 font-bold text-white hover:bg-[#bb7039]">
                            Export My Grocery List
                        </button>
                    </div>
                </div>

                <!-- Recipe Video & Steps -->
                <div class="flex basis-4/6 flex-col gap-8">
                    <div class="flex h-fit flex-row items-center gap-5 rounded-lg bg-white p-2 shadow-sm">
                        <i class="text-gray-400" data-feather="link"></i>
                        <input
                            type="text"
                            class="w-full rounded-md text-gray-400 placeholder:text-gray-300 focus:outline-none"
                            placeholder="Paste your Youtube Recipe URL here"
                        />
                    </div>

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

                    <div class="no-scrollbar relative flex h-screen flex-col gap-8 overflow-y-scroll">
                        <div
                            v-for="(step, stepIndex) in recipe"
                            :key="stepIndex"
                            @click="updateActiveStep(step, stepIndex)"
                            :class="stepIndex !== state.activeStepIndex ? 'opacity-30 transition-all hover:opacity-80' : ''"
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
