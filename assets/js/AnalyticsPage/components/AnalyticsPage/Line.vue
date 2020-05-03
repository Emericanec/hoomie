<script>
    import {Line, mixins} from 'vue-chartjs'

    const {reactiveProp} = mixins;

    export default {
        extends: Line,
        mixins: [reactiveProp],
        name: 'AnalyticsLine',
        props: ['data'],
        mounted() {
            this.renderChart(this.data, {
                maintainAspectRatio: false
            })
        }
    }
</script>

<style scoped>

</style>