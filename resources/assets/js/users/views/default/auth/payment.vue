<style scoped>
    .credit-card-inputs.complete {
        border: 2px solid green;
    }

    .StripeElement {
        background-color: white;
        padding: 8px 12px;
        border-radius: 4px;
        border: 1px solid transparent;
        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
        border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
</style>
<template>
    <div id='app'>
        <div v-if="show">
            <div class="col-12 plan">
                <div class="p-lg-2 pt-sm-5 p-3">
                    <div class="col-12 col-lg-8 offset-lg-2 plan-form">
                        <h5>STEP <b style="color:#3498db;">3</b> OF 3</h5>
                        <h3>Set up your payment.</h3>
                        <div class=" col-lg-10 offset-lg-1 ">
                            <card class='stripe-card pt-4'
                                  :class='{ complete }'
                                  :stripe='$Helper.stripe_key()'
                                  :options='stripeOptions'
                                  @change='complete = $event.complete'
                            />
                            <small>No commitments, Cancel online at anytime.</small>
                            <small class="is-danger">{{error}}</small>
                            <br>
                            <button v-if="!button_loading" class='btn btn-outline-primary mt-4 pay-with-stripe' @click='pay'>START MEMBERSHIP</button>
                            <button v-if="button_loading" class="btn btn-outline-primary" disabled><i class="fa fa-circle-o-notch fa-spin"></i> Loading</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="finish">
            <div class="col-12 plan">
                <div class="col-4">
                    <a class="navbar-brand" href="#">THE <b style="color:#3498db;">{{$t('app_name')}}</b></a>
                </div>
                <div class="p-lg-2 pt-sm-5 p-3">
                    <div class="col-12 col-lg-8 offset-lg-2 plan-form">
                        <h3>Welcome To {{$t('app_name')}}</h3>
                        <h5>Your {{$t('app_name')}} membership, which begins with a 1 week free trial.</h5>
                        <p>Cancel anytime before {{trail_end}} and will not be charged, to cancel go to your account setting and Canel Membership.</p>
                        <br>
                        <h4>Your account details</h4>
                        <ul>
                            <li>Name: {{name}} </li>
                            <li>Email: {{email}}</li>
                            <li>Payment Information:  ***********{{card_number}}</li>
                        </ul>
                        <router-link role="button" class="btn btn-primary" :to="{name: 'profile'}">Finish</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BounceLoader from "vue-spinner/src/BounceLoader.vue";
    import {
        Card,
        createToken,
        CardNumber,
        CardExpiry,
        CardCvc
    } from "vue-stripe-elements-plus";
    export default {
        props: ["stripe", "options"],
        data() {
            return {
                show: true,
                finish: false,
                error: null,
                complete: false,
                number: false,
                expiry: false,
                cvc: false,

                // info
                email: '',
                name: '',
                trail_end: '',
                plan: null,
                stripeOptions: {
                    // see https://stripe.com/docs/stripe.js#element-options for details
                },
                button_loading:false,
            };
        },
        components: {Card, CardNumber, CardExpiry, CardCvc,BounceLoader},

        created() {
            // Check Users Status
            if (this.$auth.isAuthenticated()) {
                axios
                    .get("/api/v1/get/check/user")
                    .then(response => {
                        if (response.data.status !== 'payment_step') {
                            this.$router.push({name: 'home'});
                        } else {
                            this.show = true;
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 401) {
                            this.$auth.destroyToken();
                        }
                    });
            } else {
                this.$router.push({name: 'home'});
            }
        },
        methods: {
            update() {
                this.complete = this.number && this.expiry && this.cvc;

                // field completed, find field to focus next
                if (this.number) {
                    if (!this.expiry) {
                        this.$refs.cardExpiry.focus();
                    } else if (!this.cvc) {
                        this.$refs.cardCvc.focus();
                    }
                } else if (this.expiry) {
                    if (!this.cvc) {
                        this.$refs.cardCvc.focus();
                    } else if (!this.number) {
                        this.$refs.cardNumber.focus();
                    }
                }
                // no focus magic for the CVC field as it gets complete with three
                // numbers, but can also have four
            },
            pay() {
                this.button_loading = true;
                // createToken returns a Promise which resolves in a result object with
                // either a token or an error key.
                // See https://stripe.com/docs/api/v1/#tokens for the token object.
                // See https://stripe.com/docs/api/v1/#errors for the error object.
                // More general https://stripe.com/docs/stripe.js#stripe-create-token.
                createToken().then(data => {
                    if(data.error === undefined) {
                    if (data.token.id !== null) {
                        if(localStorage.getItem('_plan') !== undefined || localStorage.getItem('_plan') !== null){
                            this.plan = localStorage.getItem('_plan');
                        }else{
                            this.plan = 1;
                        }
                        axios.post("/api/v1/register/payment", {token: data.token.id, plan: this.plan}).then(
                            res => {
                                if (res.data.status === "success") {
                                    this.show = false
                                    this.button_loading = false;
                                    this.email = res.data.email;
                                    this.name = res.data.name;
                                    this.trail_end = res.data.trail_end;
                                    this.card_number = res.data.card_number;
                                    this.card_brand = res.data.card_brand;
                                    this.finish = true;
                                    localStorage.removeItem('_plan');
                                }
                            },error => {
                                if (error.response.status === 500) {
                                    this.error = error.response.data.message;
                                    this.button_loading = false;
                                }
                            }
                        );
                    }
                    }else{
                      this.button_loading = false;
                    }
                });
            },
            
            LOG_OUT() {
                this.$auth.destroyToken();
                this.$router.go('/')
            },
        }
    };
</script> 
 
