Vue.component('plan-services', {
    props: ['previousServices'],
    data() {
        return {
            title: "Customize the plan exactly on your needs",
            description:
                "Please fill in the next fields to ensure you the best plan for you",
            img: "",
            isLoading: false,
            services: [],
            query: {},
            requirements: "",
            usersNum: 1,
            selectedServices: []
        };
    },
    mounted(){
        console.log(this.previousServices)
        this.query= this.getCurrentQueryString()
        this.getData()
    },
    methods: {
        async getData() {
            this.isLoading = true;
            this.services= await productAddons.get(this.query.plan_id);
            console.log(this.services);
            this.isLoading = false;
        },
        selectService(service){
            let data= {
                service: service.ID,
                Quantity: this.usersNum,
                Terms: this.requirements,
                Calculation_Type: service.Calculation_Type,
                Add_Ons1: service.ID
            }
            this.selectedServices.push(data)
            this.usersNum= 1;
            this.requirements= ""
            // jQuery('.modal').modal('hide')
        },
        unSelectService(service){
            this.selectedServices = this.selectedServices.filter(s => service.ID !== s.service)
        },
        proceed(){
            let url= this.buildQueryString('/form2', {});
            var data= {
                selectedServices: this.previousServices.selectedServices,
                selectedAddons: this.selectedServices,
            }
            var form = jQuery('<form action="' + url + '" method="post">' +
                '<textarea name="api_data" >' + JSON.stringify(data) + '</textarea>' +
                '</form>');
            jQuery('body').append(form);
            console.log(JSON.stringify(data))
            form.submit();
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
        }
    }
});

new Vue({
    el: document.getElementById('vue-container')
});
