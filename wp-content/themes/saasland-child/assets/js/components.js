Vue.use(VueLoading);
Vue.component('loading', VueLoading)
Vue.component('header-wizard', {
    name: "HeaderWizard",
    data(){
        return {
            steps: [
                {
                    name: "Basic Information"
                },
                {
                    name: "Support Packages"
                },
                {
                    name: "Professional Services"
                },
                {
                    name: "Addons"
                },
                {
                    name: "Personal Information"
                },
                {
                    name: "Summary"
                },
            ]
        }
    }
});
Vue.component('header-component', {
    name: "HeaderComponent",
    props: ["title", "description", "img"],
});
Vue.component('service-component', {
    name: "ServiceComponent",
    props: ["title", "description", "index", "service"],
    methods: {
    }
});
Vue.component('pricing-card', {
    props: ["plan", "index", "features"],
    methods: {
        buildQueryString(url, params){
            var esc = encodeURIComponent;
            var query = Object.keys(params)
                .map(k => esc(k) + '=' + esc(params[k]))
                .join('&');
            return url + "?" + query
        },
        getImageNum(index){
            return (index % 3) + 1
        }
    }
});
