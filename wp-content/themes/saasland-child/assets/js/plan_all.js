Vue.component('plan-all', {
    data() {
        return {
            title: "Main User License Plans",
            description:
                "Explore our user licenses plans for SwiftAssess, which includes a variety of features to help you achieve more",
            img: "header-img1.png",
            typeId: "",
            isLoading: false,
            plans: [],
            features: [],
            planFeatures: []
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
            let features = await productFeatures.get(this.typeId);
            let finalFeatures= {};
            await features.forEach(feature => {
                if (!this.planFeatures[feature.Product_Plan.ID])
                    this.planFeatures[feature.Product_Plan.ID] = {};

                this.planFeatures[feature.Product_Plan.ID][feature.Sub_Feature.ID]= feature.Type;

                if (!finalFeatures[feature.Main_Feature.ID])
                    finalFeatures[feature.Main_Feature.ID] = {
                        ID: feature.Main_Feature.ID,
                        display_value: feature.Main_Feature.display_value,
                        sub: {},
                        Tooltip_Information: feature["Main_Feature.Tooltip_Information"],
                        Is_Applicable: feature["Main_Feature.Is_Applicable"]
                    };

                finalFeatures[feature.Main_Feature.ID].sub[feature.Sub_Feature.ID]= {
                    ID: feature.Sub_Feature.ID,
                    display_value: feature.Sub_Feature.display_value,
                    Tooltip_Information: feature["Sub_Feature.Tooltip_Information"],
                    Is_Applicable: feature["Sub_Feature.Is_Applicable"]
                };
            });
            console.log(finalFeatures)
            this.features= finalFeatures;

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
