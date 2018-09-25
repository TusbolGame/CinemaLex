<template>
  <div>
    <div class="dashboard">
      <h5 class="title">Dashboard</h5>

      <div class="users-chart" v-if="chart_type === 1">
        <div class="row">
          <div class="col-6">
            <div class="button-section mt-3 ml-5">
              <button class="btn btn-sm btn-outline-primary ml-1" :class="{active: chart_type === 1}" @click="chart_type=1">Users</button>
              <button class="btn btn-sm btn-outline-primary ml-1" :class="{active: chart_type === 2}" @click="chart_type=2">Top</button>
            </div>
          </div>

          <div class="col-6">
            <div class="button-section float-right mt-3 ml-5">
              <button class="btn btn-sm btn-outline-primary ml-1" :class="{active: users_analysis_active === 'day'}" @click="users_analysis_active = 'day'">DAY</button>
              <button class="btn btn-sm btn-outline-primary ml-1" :class="{active: users_analysis_active === 'month'}" @click="users_analysis_active = 'month'">MONTH</button>
              <button class="btn btn-sm btn-outline-primary ml-1" :class="{active: users_analysis_active === 'year'}" @click="users_analysis_active = 'year'">YEAR</button>
            </div>
          </div>

        </div>

        <hr>

        <div class="col-12 row" v-if="chart_type === 1 && users_analysis_active == 'day'">
          <div class="col-12 col-sm-6 col-lg-6">

            <div class="col-12 m-2 details">
              <span class="header">Active User</span>
              <users-day-chart :data="activeUserDay" :options="options" :height="150"></users-day-chart>
            </div>

          </div>

          <div class="col-12 col-sm-6 col-lg-6">

            <div class="col-12 m-2 details">
              <span class="header">Inactive User</span>
              <users-day-chart :data="inactiveUserDay" :options="options" :height="150"></users-day-chart>
            </div>

          </div>

        </div>

        <!-- END User Chart Day -->


        <div class="col-12 row" v-if="chart_type === 1 && users_analysis_active == 'month'">

          <div class="col-12 col-sm-6 col-lg-6">

            <div class="col-12 m-2 details">
              <span class="header">Active User</span>
              <users-month-chart :data="activeUserMonth" :options="options" :height="150"></users-month-chart>
            </div>

          </div>

          <div class="col-12 col-sm-6 col-lg-6">

            <div class="col-12 m-2 details">
              <span class="header">Inactive User</span>
              <users-month-chart :data="inactiveUserMonth" :options="options" :height="150"></users-month-chart>
            </div>

          </div>
        </div>

        <!-- END User Chart Month -->


        <div class="col-12 row" v-if="chart_type === 1 && users_analysis_active == 'year'">

          <div class="col-12 col-sm-6 col-lg-6">

            <div class="col-12 m-2 details">
              <span class="header">Active User</span>
              <users-year-chart :data="activeUserYear" :options="options" :height="150"></users-year-chart>
            </div>

          </div>

          <div class="col-12 col-sm-6 col-lg-6">

            <div class="col-12 m-2 details">
              <span class="header">Inactive User</span>
              <users-year-chart :data="inactiveUserYear" :options="options" :height="150"></users-year-chart>
            </div>

          </div>

        </div>

        <!-- END User Chart Year -->
      </div>

      <!-- END User Chart -->

      <div class="top-chart" v-if="chart_type === 2">
        <div class="row">
          <div class="col-6">
            <div class="button-section mt-3 ml-5">
              <button class="btn btn-sm btn-outline-primary ml-1" :class="{active: chart_type === 1}" @click="chart_type=1">Users</button>
              <button class="btn btn-sm btn-outline-primary ml-1" :class="{active: chart_type === 2}" @click="chart_type=2">Top</button>
            </div>
          </div>

          <div class="col-6">
            <div class="button-section float-right mt-3 ml-5">
              <button class="btn btn-sm btn-outline-primary ml-1" :class="{active: top_analysis_active === 'day'}" @click="top_analysis_active = 'day'">DAY</button>
              <button class="btn btn-sm btn-outline-primary ml-1" :class="{active: top_analysis_active === 'month'}" @click="top_analysis_active = 'month'">MONTH</button>
              <button class="btn btn-sm btn-outline-primary ml-1" :class="{active: top_analysis_active === 'year'}" @click="top_analysis_active = 'year'">YEAR</button>
            </div>
          </div>

        </div>

        <hr>
        <div class="col-12 row" v-if="chart_type === 2 && top_analysis_active == 'day'">
          <div class="col-12">

            <div class="col-12 m-2 details">
              <span class="header">MOST WATCH MOVIES AND EPISODE (Hourly)</span>
              <top-day-chart :data="topDay" :options="options" :height="150"></top-day-chart>
            </div>

          </div>

        </div>

        <!-- END Top Chart Day -->


        <div class="col-12 row" v-if="chart_type === 2 && top_analysis_active == 'month'">

          <div class="col-12">

            <div class="col-12 m-2 details">
              <span class="header">MOST WATCH MOVIES AND EPISODE (Monthly)</span>
              <top-month-chart :data="topMonth" :options="options" :height="150"></top-month-chart>
            </div>

          </div>
        </div>

        <!-- END Top Chart Month -->


        <div class="col-12 row" v-if="chart_type ===2 && top_analysis_active == 'year'">

          <div class="col-12">

            <div class="col-12 m-2 details">
              <span class="header">MOST WATCH MOVIES AND EPISODE (Yearly)</span>
              <users-year-chart :data="topYear" :options="options" :height="150"></users-year-chart>
            </div>

          </div>
        </div>
      </div>

      <!-- END Top Chart -->

      <hr>

      <div class="crms  text-center offset-sm-2 p-2">
        <div class="col-12 row">

          <div class="col-12  col-sm-2 col-lg-2 count">
            <div class="details">
              <h4 class="reports">{{total.reports}}</h4>
              <span class="header">Reports</span>
            </div>
          </div>

          <div class="col-12  col-sm-2 col-lg-2 count">
            <div class="details">
              <h4 class="movies">{{total.movies}}</h4>
              <span class="header">Movies</span>
            </div>
          </div>

          <div class="col-12  col-sm-2 col-lg-2 count">
            <div class="details">
              <h4 class="series">{{total.series}}</h4>
              <span class="header">Series</span>
            </div>
          </div>
          <div class="col-12  col-sm-2 col-lg-2 count">
            <div class="details">
              <h4 class="episode">{{total.episodes}}</h4>
              <span class="header">Episode</span>
            </div>
          </div>
          <div class="col-12  col-sm-2 col-lg-2 count">
            <div class="details">
              <h4 class="users">{{total.users}}</h4>
              <span class="header">Users</span>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="col-12">
        <div class="row">
          <div class="col-4">
            <b class="title ml-3">Top users</b>
            <ul class="list-group">
              <li class="list-group-item d-flex justify-content-between align-items-center" v-for="(item,index) in top_all_time.users" :key="index"
                v-if="item.name !== null">
                {{item.name}}
                <span class="badge badge-primary badge-pill">{{item.user_count}}</span>
              </li>
            </ul>
          </div>
          <div class="col-4">
            <b class="title ml-3">Top movies</b>
            <ul class="list-group">
              <li class="list-group-item d-flex justify-content-between align-items-center" v-for="(item,index) in top_all_time.movies" :key="index"
                v-if="item.m_name !== null">
                {{item.m_name}}
                <span class="badge badge-primary badge-pill">{{item.movie_count}}</span>
              </li>
            </ul>
          </div>
          <div class="col-4">
            <b class="title ml-3">Top series</b>
            <ul class="list-group">
              <li class="list-group-item d-flex justify-content-between align-items-center" v-for="(item,index) in top_all_time.series" :key="index"
                v-if="item.t_name !== null">
                {{item.t_name}}({{item.name}})
                <span class="badge badge-primary badge-pill">{{item.series_count}}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </div>

</template>
<script>
import usersday from "./charts/users.js";
import usersmonth from "./charts/users.js";
import usersyear from "./charts/users.js";
import topday from "./charts/users.js";
import topmonth from "./charts/users.js";
import topyear from "./charts/users.js";
export default {
  components: {
    "users-day-chart": usersday,
    "users-month-chart": usersmonth,
    "users-year-chart": usersyear,
    "top-day-chart": topday,
    "top-month-chart": topmonth,
    "top-year-chart": topyear
  },
  data() {
    return {
      show_chart: true,
      chart_type: 1,
      users_analysis_active: "day",
      top_analysis_active: "day",
      total: null,
      top_all_time: [],

      activeUserDay: {
        perecent: {},
        all: 0,
        labels: [],
        datasets: [
          {
            label: "Active",
            yAxisID: "Active",
            backgroundColor: "rgba(3, 169, 244, 0.7)",
            pointBackgroundColor: "#818181",
            borderWidth: 1,
            borderColor: "#818181",
            pointBorderColor: "#fff",
            //Data to be represented on y-axis
            data: []
          }
        ]
      },

      inactiveUserDay: {
        perecent: {},
        all: 0,
        labels: [],
        datasets: [
          {
            label: "Active",
            yAxisID: "Active",
            backgroundColor: "rgba(233, 30, 99, 0.7)",
            pointBackgroundColor: "#818181",
            borderWidth: 1,
            borderColor: "#818181",
            pointBorderColor: "#fff",
            //Data to be represented on y-axis
            data: []
          }
        ]
      },

      activeUserMonth: {
        perecent: {},
        all: 0,
        labels: [],
        datasets: [
          {
            label: "Active",
            yAxisID: "Active",
            backgroundColor: "rgba(3, 169, 244, 0.7)",
            pointBackgroundColor: "#818181",
            borderWidth: 1,
            borderColor: "#818181",
            pointBorderColor: "#fff",
            //Data to be represented on y-axis
            data: []
          }
        ]
      },

      inactiveUserMonth: {
        perecent: {},
        all: 0,
        labels: [],
        datasets: [
          {
            label: "Active",
            yAxisID: "Active",
            backgroundColor: "rgba(233, 30, 99, 0.7)",
            pointBackgroundColor: "#818181",
            borderWidth: 1,
            borderColor: "#818181",
            pointBorderColor: "#fff",
            //Data to be represented on y-axis
            data: []
          }
        ]
      },

      activeUserYear: {
        perecent: {},
        all: 0,
        labels: [],
        datasets: [
          {
            label: "Active",
            yAxisID: "Active",
            backgroundColor: "rgba(3, 169, 244, 0.7)",
            pointBackgroundColor: "#818181",
            borderWidth: 1,
            borderColor: "#818181",
            pointBorderColor: "#fff",
            //Data to be represented on y-axis
            data: []
          }
        ]
      },

      inactiveUserYear: {
        perecent: {},
        all: 0,
        labels: [],
        datasets: [
          {
            label: "Active",
            yAxisID: "Active",
            backgroundColor: "rgba(233, 30, 99, 0.7)",
            pointBackgroundColor: "#818181",
            borderWidth: 1,
            borderColor: "#818181",
            pointBorderColor: "#fff",
            //Data to be represented on y-axis
            data: []
          }
        ]
      },

      topDay: {
        perecent: {},
        all: 0,
        labels: [],
        datasets: [
          {
            label: "Active",
            yAxisID: "Active",
            backgroundColor: "rgba(233, 30, 99, 0.7)",
            pointBackgroundColor: "#818181",
            borderWidth: 1,
            borderColor: "#818181",
            pointBorderColor: "#fff",
            //Data to be represented on y-axis
            data: []
          }
        ]
      },

      topMonth: {
        perecent: {},
        all: 0,
        labels: [],
        datasets: [
          {
            label: "Active",
            yAxisID: "Active",
            backgroundColor: "rgba(3, 169, 244, 0.7)",
            pointBackgroundColor: "#818181",
            borderWidth: 1,
            borderColor: "#818181",
            pointBorderColor: "#fff",
            //Data to be represented on y-axis
            data: []
          }
        ]
      },

      topYear: {
        perecent: {},
        all: 0,
        labels: [],
        datasets: [
          {
            label: "Active",
            yAxisID: "Active",
            backgroundColor: "rgba(233, 30, 99, 0.7)",
            pointBackgroundColor: "#818181",
            borderWidth: 1,
            borderColor: "#818181",
            pointBorderColor: "#fff",
            //Data to be represented on y-axis
            data: []
          }
        ]
      },

      //Chart.js options that controls the appearance of the chart
      options: {
        scales: {
          yAxes: [
            {
              id: "Active",
              type: "linear",
              position: "left",
              ticks: {
                beginAtZero: false,
                display: false
              },
              gridLines: {
                display: false
              }
            }
          ],
          xAxes: [
            {
              gridLines: {
                display: false
              }
            }
          ],
          zAxes: [
            {
              ticks: {
                display: false
              }
            }
          ]
        },
        legend: {
          display: false
        },
        responsive: true,
        maintainAspectRatio: false
      }
    };
  },

  mounted() {
    axios.get("/api/admin/get/dashboard/analysis").then(response => {
      if (response.status === 200) {
        // Active and Inactivity User
        // Day
        for (
          let index = 0;
          index < response.data.data.users.day.length;
          index++
        ) {
          if (response.data.data.users.day[index].type === "active") {
            //Active
            this.activeUserDay.labels.push(
              response.data.data.users.day[index].hour
            );
            this.activeUserDay.datasets[0].data.push(
              response.data.data.users.day[index].number
            );
          } else {
            //Inactive
            this.inactiveUserDay.labels.push(
              response.data.data.users.day[index].hour
            );
            this.inactiveUserDay.datasets[0].data.push(
              response.data.data.users.day[index].number
            );
          }
        }

        //Month
        for (
          let index = 0;
          index < response.data.data.users.month.length;
          index++
        ) {
          if (response.data.data.users.month[index].type === "active") {
            //Active
            this.activeUserMonth.labels.push(
              response.data.data.users.month[index].month
            );
            this.activeUserMonth.datasets[0].data.push(
              response.data.data.users.month[index].number
            );
          } else {
            //Inactive
            this.inactiveUserMonth.labels.push(
              response.data.data.users.month[index].month
            );
            this.inactiveUserMonth.datasets[0].data.push(
              response.data.data.users.month[index].number
            );
          }
        }

        //Year
        for (
          let index = 0;
          index < response.data.data.users.year.length;
          index++
        ) {
          if (response.data.data.users.year[index].type === "active") {
            //Active
            this.activeUserYear.labels.push(
              response.data.data.users.year[index].year
            );
            this.activeUserYear.datasets[0].data.push(
              response.data.data.users.year[index].number
            );
          } else {
            //Inactive
            this.inactiveUserYear.labels.push(
              response.data.data.users.year[index].year
            );
            this.inactiveUserYear.datasets[0].data.push(
              response.data.data.users.year[index].number
            );
          }
        }

        // Top movie and series
        // Day
        for (
          let index = 0;
          index < response.data.data.top.day.length;
          index++
        ) {
          this.topDay.labels.push(response.data.data.top.day[index].name);
          this.topDay.datasets[0].data.push(
            response.data.data.top.day[index].count
          );
        }

        //Month
        for (
          let index = 0;
          index < response.data.data.top.month.length;
          index++
        ) {
          this.topMonth.labels.push(response.data.data.top.month[index].name);
          this.topMonth.datasets[0].data.push(
            response.data.data.top.month[index].count
          );
        }

        //Year
        for (
          let index = 0;
          index < response.data.data.top.year.length;
          index++
        ) {
          this.topYear.labels.push(response.data.data.top.year[index].name);
          this.topYear.datasets[0].data.push(
            response.data.data.top.year[index].count
          );
        }

        this.total = response.data.data.count;
        this.top_all_time = response.data.data.top_all_time;
      }
    });

  },

  methods: {}
};
</script>