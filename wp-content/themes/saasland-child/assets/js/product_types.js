Vue.component('product-types', {
    data() {
        return {
            isLoading: false,
            title: "Plans and Pricing",
            description: "Plans built for every organization and institution",
            img: "",
            productTypes: [],
            productPlans: [],
            hostings: [],
            currentType: "",
            currentPlans: [],
            features: {}
        };
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        async fetchData() {
            this.isLoading = true;
            this.productTypes = await productTypes.get();
            if (this.productTypes.length >= 1) {
                let uri = window.location.search.substring(1);
                let params = new URLSearchParams(uri);
                if(params.get("typeId")){
                    this.currentType =params.get("typeId")
                }else{
                    this.currentType = this.productTypes[0].ID;
                }
                this.currentPlans = this.productPlans[this.productTypes[0].ID];
            }
            let plans = await productPlans.get();
            plans.forEach(plan => {
                if (!this.productPlans[plan.Product_Type.ID])
                    this.productPlans[plan.Product_Type.ID] = [];
                this.productPlans[plan.Product_Type.ID].push(plan);
            });
            let features = await productFeatures.get();
            let finalFeatures= {};
            await features.forEach(feature => {
                if (!finalFeatures[feature.Product_Plan.ID])
                    finalFeatures[feature.Product_Plan.ID] = {};

                if (!finalFeatures[feature.Product_Plan.ID][feature.Main_Feature.ID])
                    finalFeatures[feature.Product_Plan.ID][feature.Main_Feature.ID] = {
                        ID: feature.Main_Feature.ID,
                        display_value: feature.Main_Feature.display_value,
                        sub: [],
                        Tooltip_Information: feature["Main_Feature.Tooltip_Information"],
                        Is_Applicable: feature["Main_Feature.Is_Applicable"]
                    };

                finalFeatures[feature.Product_Plan.ID][feature.Main_Feature.ID].sub.push({
                    ID: feature.Sub_Feature.ID,
                    display_value: feature.Sub_Feature.display_value,
                    Tooltip_Information: feature["Sub_Feature.Tooltip_Information"],
                    Is_Applicable: feature["Sub_Feature.Is_Applicable"]
                });
            });
            console.log(finalFeatures)
            this.features= finalFeatures;
            if (this.productTypes.length >= 1) {
                this.currentPlans = this.productPlans[this.currentType];
            }
            this.isLoading = false;
        },
        changeType(type) {
            this.currentType = type;
            this.currentPlans = this.productPlans[type];
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
        }
    }
});

new Vue({
    el: document.getElementById('vue-container')
})
