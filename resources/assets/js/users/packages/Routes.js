import VueRouter from 'vue-router';

let themeName = document.body.firstElementChild.className;

let routes = [

    {
        name: 'home',
        path: '/v1',
        component: require('../views/' + themeName + '/home'),
        meta: {
            userAuth: true
        }
    },

    {
        name: 'login',
        path: '/v1/login',
        component: require('../views/' + themeName + '/auth/login'),
        meta: {
            userAuth: true
        }
    },

    {
        name: 'plan',
        path: '/v1/signup/plan',
        component: require('../views/' + themeName + '/auth/plan'),
        meta: {
            userAuth: true
        }
    },
    {
        name: 'signup',
        path: '/v1/signup',
        component: require('../views/' + themeName + '/auth/register'),
        meta: {
            userAuth: true
        }
    },
    {
        name: 'forget_password',
        path: '/v1/forget',
        component: require('../views/' + themeName + '/auth/forget-password'),
        meta: {
            userAuth: true
        }
    },

    {
        name: 'forget_change',
        path: '/v1/forget/rest/:code',
        component: require('../views/' + themeName + '/auth/forget-rest'),
        meta: {
            userAuth: true
        }
    },

    {
        name: 'privacy',
        path: '/v1/privacy',
        component: require('../views/' +themeName+ '/control/footer/privacy'),
        meta: {
            userAuth: true
        }
    },
    {
        name: 'terms',
        path: '/v1/terms',
        component: require('../views/' +themeName+ '/control/footer/terms'),
        meta: {
            userAuth: true
        }
    },
    {
        name: 'contact-us',
        path: '/v1/contact-us',
        component: require('../views/' +themeName+ '/control/footer/contact-us'),
        meta: {
            userAuth: true
        }
    },
    {
        name: 'about-us',
        path: '/v1/about-us',
        component: require('../views/' +themeName+ '/control/footer/about-us'),
        meta: {
            userAuth: true
        }
    },
    {
        name: '404',
        path: '/v1/404',
        component: require('../views/' +themeName+ '/errors/404'),
    },
    {
        name: 'payment',
        path: '/v1/signup/payment',
        component: require('../views/' + themeName + '/auth/payment'),
        meta: {
            userNotAuth: true
        }
    },
    {
        name: 'payment-reactive',
        path: '/v1/signup/payment-reactive',
        component: require('../views/' + themeName + '/auth/payment_reactive'),
        meta: {
            userNotAuth: true
        }
    },


	{
		name: 'discover',
		path: '/v1/app',
        component: require('../views/' +themeName+ '/control/home'),
        meta: {
            userNotAuth: true
        }
	},

    {
        name: 'movies',
        path: '/v1/app/movies',
        component: require('../views/' + themeName + '/control/movie/movies'),
                meta: {
            userNotAuth: true
        }
    },
	{
		name:'movie-show',
		path: '/v1/app/movie/show/:id',
        component: require('../views/' +themeName+ '/control/movie/show'),
                meta: {
            userNotAuth: true
        }
	},
	{
		name: 'series',
		path: '/v1/app/series',
        component: require('../views/' +themeName+ '/control/series/series'),
        meta: {
            userNotAuth: true
        }
	},
		
	{
        name:'series-show',
        path: '/v1/app/series/show/:id',
        component: require('../views/' +themeName+ '/control/series/show'),
        meta: {
            userNotAuth: true
        }
	},
    {
        name: 'kids',
        path: '/v1/app/kids',
        component: require('../views/' +themeName+ '/control/kids/kids'),
        meta: {
            userNotAuth: true
        }
    },
    {
        name: 'channels',
        path: '/v1/app/channels',
        component: require('../views/' +themeName+ '/control/channels/channels'),
        meta: {
            userNotAuth: true
        }
    },
	{
		name: 'collection',
        path: '/v1/app/collection/:id',
        component: require('../views/' +themeName+ '/control/collection/index.vue'),
        meta: {
            userNotAuth: true
        }
    },
    {
        name: 'series-player-specific',
        path: '/v1/app/watch/series/:episode_number/:series_id',
        component: require('../views/' + themeName + '/control/video-player/series-player-specific'),
        meta: {
            userNotAuth: true
        }
    },

	{
        name: 'series-player-current',
        path: '/v1/app/watch/series/:series_id',
        component: require('../views/' + themeName +'/control/video-player/series-player-current'),
        meta: {
            userNotAuth: true
        }
	},
	{
		name: 'movie-player',
		path: '/v1/app/watch/movie/:id',
		component: require('../views/' +themeName+ '/control/video-player/movie-player'),
        meta: {
            userNotAuth: true
        }
    },
    
    {
        name: 'tv-player',
        path: '/v1/app/watch/tv/:id',
        component: require('../views/' +themeName+ '/control/video-player/tv-player'),
        meta: {
            userNotAuth: true
        }
    },
	{
		name: 'search',
		path: '/v1/app/search/:search',
        component: require('../views/' + themeName + '/control/search/search'),
                     meta: {
            userNotAuth: true
        }
	},
	{
		name: 'cast',
		path: '/v1/app/cast/:id',
        component: require('../views/' + themeName + '/control/search/cast'),
                     meta: {
            userNotAuth: true
        }
	},

    {
        name: 'profile',
        path: '/v1/app/setting/public',
        component: require('../views/' +themeName+ '/control/setting/profile'),
		meta: {
            userNotAuth: true
		}
    },
    {
        name: 'security',
        path: '/v1/app/setting/security',
        component: require('../views/' +themeName+ '/control/setting/security'),
        meta: {
            userNotAuth: true
        }
	},
	
	{
        name: 'payment-update',
        path: '/v1/app/setting/payment-update',
        component: require('../views/' +themeName+ '/control/setting/payment-update'),
        meta: {
            userNotAuth: true
        }
	},
		
	{
        name: 'billing-details',
        path: '/v1/app/setting/billing-details',
        component: require('../views/' +themeName+ '/control/setting/billing-details'),
        meta: {
            userNotAuth: true
        }
	},
	{
        name: 'change-plan',
        path: '/v1/app/setting/change-plan',
        component: require('../views/' +themeName+ '/control/setting/change-plan'),
        meta: {
            userNotAuth: true
        }
    },
    {
        name: 'language',
        path: '/v1/app/setting/language',
        component: require('../views/' +themeName+ '/control/setting/language'),
        meta: {
            userNotAuth: true
        }
    },

    {
        name: 'adjust-subtitles',
        path: '/v1/app/setting/adjust-subtitles',
        component: require('../views/' +themeName+ '/control/setting/adjust-subtitles'),
        meta: {
            userNotAuth: true
        }
    },

    {
        name: 'viewing-history',
        path: '/v1/app/setting/viewing-history',
        component: require('../views/' +themeName+ '/control/setting/viewing-history.vue'),
        meta: {
            userNotAuth: true
        }
    },

];

export default new VueRouter({
    mode: 'history',
	routes,
});