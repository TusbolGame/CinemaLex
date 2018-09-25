import router from './Routes';
export default function (Vue) {

    Vue.auth = {
        //Set token
        setToken(accessToken, expiration, username, image, language) {
            localStorage.setItem('_user',
                JSON.stringify({
                    'token': accessToken,
                    'expiration': expiration,
                    'username': username,
                    'image': image,
                    'language': language,
                }))
        },

        //Destroy token
        destroyToken(accessToken, expiration) {
            localStorage.removeItem('_user');
            router.go('/')
        },
        //Destroy token
        destroyTokenWithoutReload(accessToken, expiraion) {
            localStorage.removeItem('_user');
            router.go('/')
        },
        //Get token and check it 
        getToken() {
            let data = JSON.parse(localStorage.getItem('_user'))

            if (data !== null) {
                if (!data.token || !data.expiration) {
                    return null
                }
                if (Date.now() < data.expiration) {
                    this.destroyToken();
                    return null
                } else {
                    return data.token
                }
            }
        },


        //Get token and check it 
        getUserInfo(request) {
            if (this.getToken()) {
                const data = JSON.parse(localStorage.getItem('_user'))
                if (request === 'permission') {
                    return data.permission;
                } else if (request === 'username') {
                    return data.username;
                } else if (request === 'image') {
                    return data.image;
                }
            }
        },

        // Check if there token
        isAuthenticated() {
            if (this.getToken()) {
                return true
            } else {
                return false
            }

        },

        // Set image and username
        setUpdate(username, image, language) {
            const data = JSON.parse(localStorage.getItem('_user'))
            if (username !== null) {
                data.username = username;
            }
            if (image !== null) {
                data.image = image
            }
            if (language !== null) {
                data.language = language
            }
            localStorage.setItem("_user", JSON.stringify(data)); //put the object back
        },

    }
    //make auth global 
    Object.defineProperties(Vue.prototype, {
        $auth: {
            get: () => {
                return Vue.auth
            }
        }
    })
}