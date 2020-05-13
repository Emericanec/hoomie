<script>
    import {Line, mixins} from 'vue-chartjs'

    const {reactiveProp} = mixins;

    export default {
        extends: Line,
        mixins: [reactiveProp],
        name: 'AnalyticsLine',
        props: ['data', 'options'],
        mounted() {
            this.renderChart(this.data, this.options)
        }
    }
</script>

<style scoped>

</style>