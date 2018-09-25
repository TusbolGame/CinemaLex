//Importing Line class from the vue-chartjs wrapper
import { Line } from 'vue-chartjs'
//Exporting this so it can be used in other components
export default {
    extends: Line,
    data() {
        return {
            datacollection: {
                //Data to be represented on x-axis
                labels: ['January', 'February', 'March',],
                datasets: [
                    {
                        label: 'Data One',
                        backgroundColor: '#2196f394',
                        pointBackgroundColor: '#818181',
                        borderWidth: 1,
                        borderColor: '#818181',
                        pointBorderColor: '#fff',
                        //Data to be represented on y-axis
                        data: [40, 20, 30]
                    }
                ]
            },
            //Chart.js options that controls the appearance of the chart
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: false,
                            display: false,
                        },
                        gridLines: {
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                    }],
                    zAxes: [{
                        ticks: {
                            display: false
                        },
                    }]
                },
                legend: {
                    display: false,
                },
                responsive: true,
                maintainAspectRatio: false
            }
        }
    },
    mounted() {
        //renderChart function renders the chart with the datacollection and options object.
        this.renderChart(this.datacollection, this.options)
    }
}
