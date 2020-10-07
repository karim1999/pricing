Vue.component('form1', {
    data() {
        return {
            title: "Customize the plan exactly on your needs",
            description:
                "Please fill in the next fields to ensure you the best plan for you",
            img: "",
            plan_id: "",
            plan: "",
            product_type_id: "",
            product_type: "",
            required: "No",
            institution: "4046528000000040055",
            licenses: 0,
            vouchers: 0,
            teachers: 0,
            students: 0,
            training_type: "",
            Monthly_Yearly: "Monthly",
            n_users_training: 0,
            isLoading: false,
            institutionTypes: [],
            planData: {},
            productTrainingTypes: [],
            vouchersBundle: [],
            bundle: ""
        };
    },
    mounted(){
        let uri = window.location.search.substring(1);
        let params = new URLSearchParams(uri);
        this.plan_id = params.get("plan_id");
        this.plan = params.get("plan");
        this.product_type_id = params.get("product_type_id");
        this.product_type = params.get("product_type");
        this.getData()
    },
    methods: {
        async getData(){
            this.isLoading= true;
            this.institutionTypes= await productInstitution.get();
            this.productTrainingTypes= await productTrainingType.get();
            this.planData= await productPlans.getPlan(this.plan_id);
            if(this.product_type_id === '4046528000000040019')
                this.vouchersBundle= await vouchersBundle.get();
            this.isLoading= false
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
});
