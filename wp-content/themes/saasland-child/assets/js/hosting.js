Vue.component('hosting', {
    data() {
        return {
            title: "Main User License Plans",
            description:
                "Explore our user licenses plans for SwiftAssess, which includes a variety of features to help you achieve more",
            img: "header-img1.png",
            typeId: "",
            isLoading: false,
            plans: [],
            features: []
        };
    },
    mounted() {
        let uri = window.location.search.substring(1);
        let params = new URLSearchParams(uri);
        this.typeId = params.get("type");
        console.log(this.typeId);
        this.getData();
    },
    methods: {
        async getData() {
            this.isLoading = true;
            let plans = await productPlans.get();
            this.features = await productFeatures.get(this.typeId);
            console.log(this.features)
            plans.forEach(plan => {
                if (plan.Product_Type.ID === this.typeId) {
                    this.plans.push(plan);
                }
            });
            this.isLoading = false;
        },
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

new Vue({
    el: document.getElementById('vue-container')
});
