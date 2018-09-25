<template>
    <div>
        <div class="col-12 plan" v-if="show_page">


            <div class="col-1 back mt-1" @click="EXIT">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px"
                    viewBox="0 0 489.6 489.6" style="enable-background:new 0 0 489.6 489.6;" xml:space="preserve" width="100%"
                    class="">
                    <g>
                        <g>
                            <g>
                                <path style="fill:#21262B" d="M244.8,489.6c135,0,244.8-109.8,244.8-244.8S379.8,0,244.8,0S0,109.8,0,244.8    S109.8,489.6,244.8,489.6z M244.8,19.8c124.1,0,225,100.9,225,225s-100.9,225-225,225s-225-100.9-225-225S120.7,19.8,244.8,19.8z"
                                    data-original="#2C2F33" class="active-path" data-old_color="#21262b" />
                                <path style="fill:#3C92CA" d="M265.5,326.1c1.9,1.9,4.5,2.9,7,2.9s5.1-1,7-2.9c3.9-3.9,3.9-10.1,0-14l-67.3-67.3l67.3-67.3    c3.9-3.9,3.9-10.1,0-14s-10.1-3.9-14,0l-74.3,74.3c-3.9,3.9-3.9,10.1,0,14L265.5,326.1z"
                                    data-original="#3C92CA" class="" />
                            </g>
                        </g>
                    </g>
                </svg>
            </div>

            <!-- END back -->

            <div class="p-lg-3 pt-sm-5 p-3">
                <div class="col-12 col-lg-8 offset-lg-2 plan-form">
                    <h5>STEP
                        <b style="color:#3498db;">1</b> OF 3</h5>
                    <h3>Choose a plan that's right for you.</h3>
                    <div class="col-lg-12 text-center">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-lg-6 mt-3 text-center plan-content" @click="plan = 1" :class="{active: plan === 1}">
                                <h3>Monthly</h3>
                                <h1>$5.99
                                    <small>/mo</small>
                                </h1>
                                <i>First week free.</i>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-6 mt-3 text-center plan-content" @click="plan = 2" :class="{active: plan === 2}">
                                <h3>Annual</h3>
                                <h1>$55.99
                                    <small>/y</small>
                                </h1>
                                <i>First week free.</i>
                            </div>
                        </div>
                        <div class="mt-5">
                            <button class="btn btn-primary" @click="NEXT">Continue</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
    export default {

        data() {
            return {
                plan: 2,
                show_page: false,
            }
        },
        created() {
            if (localStorage.getItem('_plan') === '1' || localStorage.getItem('_plan') === '2') {
                this.$router.push({
                    name: 'signup'
                });
            } else {
                this.show_page = true;
            }
        },
        methods: {
            NEXT() {
                localStorage.setItem('_plan', this.plan);
                this.$router.push({
                    name: 'signup'
                });
            },
            EXIT() {
                this.$router.push('/');
            }
        }

    }
</script>