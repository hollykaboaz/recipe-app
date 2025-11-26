<script setup lang="js">
import feather from 'feather-icons';
import { onMounted, reactive } from 'vue';
import { useForm } from '@inertiajs/vue3';
import getVideoId from 'get-video-id';
import Tutorial from './Tutorial.vue';

onMounted(() => {
    feather.replace();
});

const props = defineProps({
    recipe: Object,
    videoId: String,
});

const state = reactive({
    activeStepIndex: 0,
    start: props.recipe ? recipe[0] : 0
});

const recipeForm = useForm({
    videoUrl: ''
})

function changeVideo() {
    recipeForm.videoUrl = getVideoId(recipeForm.videoUrl)['id']
    console.log(recipeForm.videoUrl)
    recipeForm.get('/')
}

function updateActiveStep(step, stepIndex) {
    state.activeStepIndex = stepIndex;
    state.start = step.timestamp_seconds;
}
</script>

<template>
    <div class="min-h-screen overflow-clip bg-orange-50 bg-cover bg-center bg-no-repeat">
        <div class="mx-auto max-w-fit md:max-w-2xl lg:max-w-6xl 2xl:max-w-7xl pt-8 mb:px-0 px-4">
            <div class="flex flex-col gap-8">
                <!-- Logo -->
                <div class="flex flex-row items-end justify-between">
                    <div class="flex flex-col leading-tight">
                        <div class="text-3xl font-black text-[#DD8F47]">
                            Just
                            <span class="text-[#ab9b8d]">Cook</span>
                            <div class="relative top-1 inline-flex">🍳</div>
                        </div>
                        <span class="text-sm font-bold text-[#DD8F47]/50 lowercase"> Straight Dish, No Garnish</span>
                    </div>

                    <p class="font-bold text-[#DD8F47]/50 text-sm">created by Holly Boaz</p>
                </div>


                <div class="flex h-fit flex-row items-center gap-5 rounded-lg bg-white p-2 shadow-sm">
                    <i class="text-gray-400" data-feather="link"></i>
                    <form class="w-full" @submit.prevent="changeVideo">
                        <input
                            v-model="recipeForm.videoUrl"
                            type="text"
                            class="w-full rounded-md text-gray-400 font-medium placeholder:text-gray-400 focus:outline-none"
                            placeholder="Paste your Youtube Recipe URL here and Press Enter"
                        />
                    </form>

                </div>

                <div
                    v-if="videoId == null"
                    class="flex flex-col gap-8 items-center align-middle">
                    <p class="text-3xl font-semibold text-[#DD8F47]/50 ">
                        Welcome to Just Cook (Beta) !
                    </p>
                    <Tutorial>
                        <img class="w-96 place-self-center rounded-lg mb-4 py-4" src="https://media3.giphy.com/media/v1.Y2lkPTZjMDliOTUyNWpkOWxjMHlrbWxjdjh2cmlvenhvd2Q3bHVlanE5eGZ2bWx2ZGNvZyZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/TWNWo8GgWdDgo8t02X/source.gif"/>
                    </Tutorial>
                    <div class="bg-blue-50 px-8 py-4 text-blue-900 rounded-lg ring-1 ring-blue-200">
                        Coming Soon: Support for Tiktok Recipes!
                    </div>
                </div>
                <div
                    v-else-if="recipeForm.processing"
                    class="flex flex-col gap-8 items-center align-middle">
                    <img src="https://media3.giphy.com/media/v1.Y2lkPTZjMDliOTUybW41ZzBvM2s1cjFnYXNiajVpYjZpOGxocDhwMjFjbGd3dzhwNW8zeSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/CNocEFcF9IBegtgW3q/giphy.gif"/>
                    <p class="text-3xl font-semibold text-[#DD8F47]/50 ">
                        Hold Tight! AI is Cooking...
                    </p>
                </div>
                <div v-else class="flex flex-col lg:flex-row gap-8">
                    <!-- Youtube Recipe Video -->
                    <div class="flex flex-col gap-4 w-full lg:w-[1000px]">
                        <iframe
                            class="grid h-[500px] content-center items-center rounded-lg"
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

                        <Tutorial class="hidden lg:block"/>

                        <div class="bg-blue-50 px-8 py-4 text-blue-900 rounded-lg ring-1 ring-blue-200">
                            Support for Tiktok coming soon!
                        </div>

                    </div>

                    <!-- Recipe Steps --->
                    <div class="no-scrollbar relative flex h-screen flex-col pb-8 gap-4 overflow-y-scroll">
                         <h2 class="font-bold text-2xl text-gray-800">
                             Recipe Steps
                         </h2>
                        <div
                            v-for="(step, stepIndex) in recipe"
                            :key="stepIndex"
                            @click="updateActiveStep(step, stepIndex)"
                            :class="stepIndex !== state.activeStepIndex ? 'opacity-30 transition-all hover:opacity-80' : ''"
                            class="flex flex-row items-start gap-x-6 rounded-lg bg-white px-3 py-4 transition-opacity duration-[1000ms] hover:cursor-pointer"
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
                                        {{ ingredient.name }}
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
