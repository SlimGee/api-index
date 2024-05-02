import { Controller } from "@hotwired/stimulus";
import ApexCharts from "apexcharts";
import numeral from "numeral";
import _ from "lodash";

// Connects to data-controller="chart"
export default class extends Controller {
    static values = {
        series: Array,
        categories: Array,
        title: String,
        subtitle: String,
        colors: Array,
        type: {
            type: String,
            default: "area",
        },
    };
    connect() {
        const optionsNew = {
            chart: {
                id: "sparkline3",
                group: "sparklines",
                type: "area",
                height: 330,
            },
            stroke: {
                curve: "straight",
            },
            fill: {
                opacity: 1,
            },
            series: this.seriesValue,
            labels: this.categoriesValue,
            xaxis: {
                type: this.seriesValue.length < 2 ? "datetime" : "category",
            },

            dataLabels: {
                enabled: false,
            },

            colors: this.colorsValue,
            //colors: ['#5564BE'],
            title: {
                text: this.titleValue,
                offsetX: 30,
                offsetY: 10,
                style: {
                    fontSize: "24px",
                    cssClass: "mt-5 !pt-10",
                },
            },
            subtitle: {
                text: this.subtitleValue,
                offsetX: 30,
                offsetY: 40,
                style: {
                    fontSize: "14px",
                    cssClass: "apexcharts-yaxis-title",
                },
            },
            yaxis: {
                title: {
                    style: {
                        // fontSize: "0px",
                    },
                },
                max:
                    Math.max(
                        ...this.seriesValue.map((series) =>
                            Math.max(...series.data),
                        ),
                    ) + 10,
            },
        };

        const chart = new ApexCharts(this.element, optionsNew);

        chart.render();
        console.log("chart rendered", this.seriesValue, this.categoriesValue);
    }
}
