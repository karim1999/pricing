Vue.component('product-packages', {
    data() {
        return {
            title: "Customize the plan exactly on your needs",
            description:
                "Please fill in the next fields to ensure you the best plan for you",
            img: "",
            isLoading: false,
            product_type_id: "",
            plans: [],
            features: [],
            plan_id: "",
            planFeatures: []
        };
    },
    mounted() {
        let uri = window.location.search.substring(1);
        let params = new URLSearchParams(uri);
        this.product_type_id = params.get("product_type_id");
        this.plan_id = params.get("plan_id");
        this.getData();
    },
    methods: {
        async getData() {
            this.isLoading = true;
            this.plans = await productPackages.get();
            let features = await productPackages.features();
            let finalFeatures= {};
            await features.forEach(feature => {
                if (!this.planFeatures[feature.Support_Package.ID])
                    this.planFeatures[feature.Support_Package.ID] = {};

                this.planFeatures[feature.Support_Package.ID][feature.Support_Packages_Feature.ID]= feature.Type;

                if (!finalFeatures[feature.Support_Packages_Feature.ID])
                    finalFeatures[feature.Support_Packages_Feature.ID] = {
                        ID: feature.Support_Packages_Feature.ID,
                        display_value: feature.Support_Packages_Feature.display_value,
                        Tooltip_Information: feature["Support_Packages_Feature.Tooltip_Information"],
                        type: feature.Type
                    };

            });
            console.log(finalFeatures)
            this.features= finalFeatures;
            this.isLoading = false;
        },
        goBack(){
            window.history.back();
        },
        getCurrentQueryString(){
            var search = location.search.substring(1);
            return JSON.parse('{"' + search.replace(/&/g, '","').replace(/=/g,'":"') + '"}', function(key, value) { return key===""?value:decodeURIComponent(value) })
        },
        buildQueryString(url, params){
            let current= this.getCurrentQueryString();
            params= {...current, ...params};
            console.log(current);
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
