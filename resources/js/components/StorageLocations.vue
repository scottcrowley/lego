<template>
    <div>
        <div class="mt-6" v-for="(typeLocations, typeName, index) in locations">
            <div class="">
                <p class="title mb-1 cursor-pointer dropdown-toggle toggle-closed" v-text="typeName" @click.prevent="toggleLocations(index, $event)"></p>
            </div>
            <div class="ml-4 pt-3" v-show="showLocation[index]">
                <div v-for="location in typeLocations" class="text-sm md:text-base py-2 px-1 border rounded mb-2 sm:flex text-secondary-700">
                    <div class="font-semibold sm:flex-1 sm:font-normal" v-text="location.name + ((location.nickname !== null) ? ' (' + location.nickname + ')' : '')"></div>
                    <div class="flex justify-around sm:block sm:mt-0 mt-2">
                        <a :href="'/storage/locations/' + location.id + '/parts'" class="btn is-small sm:mr-2">parts</a>
                        <a :href="'/storage/locations/' + location.id + '/copy'" class="btn is-small sm:mr-2">copy</a>
                        <a :href="'/storage/locations/' + location.id + '/edit'" class="btn is-small">edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'locations'
        ],
        data() {
            return {
                showLocation: [],
            }
        },
        created() {
            for(let i=0; i< Object.keys(this.locations).length; i++) {
                this.showLocation[i] = true;
            };
        },
        methods: {
            toggleLocations(index, e) {
                e.target.classList.toggle('toggle-open');
                e.target.classList.toggle('toggle-closed');
                this.$set(this.showLocation, index, !this.showLocation[index]);
            }
        },
    }
</script>