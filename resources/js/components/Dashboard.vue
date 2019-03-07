<template>
    <div class="dashboard card-container">
        <div class="card">
            <div class="card-header">Total Sets</div>
            <div class="card-body" :class="loading ? 'loader' : ''" v-text="legoData.total_sets"></div>
        </div>
        <div class="card">
            <div class="card-header">All Parts</div>
            <div class="card-body" :class="loading ? 'loader' : ''" v-text="legoData.all_parts"></div>
        </div>
        <div class="card">
            <div class="card-header">Set Parts</div>
            <div class="card-body" :class="loading ? 'loader' : ''" v-text="legoData.total_set_parts"></div>
        </div>
        <div class="card">
            <div class="card-header">Loose Parts</div>
            <div class="card-body" :class="loading ? 'loader' : ''" v-text="legoData.total_loose_parts"></div>
        </div>
        <div class="card">
            <div class="card-header">Lost Set Parts</div>
            <div class="card-body" :class="loading ? 'loader' : ''" v-text="legoData.lost_set_parts"></div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                legoData: [],
                loading: true
            }
        },

        created() {
            axios.get('/api/users/profile')
            .then(({ data }) => {
                this.loading = false;
                this.legoData = data.lego;

            });
        }
    };
</script>
