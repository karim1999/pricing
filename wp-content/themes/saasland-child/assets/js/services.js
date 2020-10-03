Vue.component('plan-services', {
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
        this.query= this.getCurrentQueryString()
        this.getData()
    },
    methods: {
        async getData() {
            this.isLoading = true;
            this.services= await productServices.get();
            console.log(this.services);
            this.isLoading = false;
        },
        selectService(service){
            let data= {
                service: service.ID,
                Quantity: this.usersNum,
                Terms: this.requirements,
                Calculation_Type: service.Calculation_Type,
                Professional_Services: service.ID,
                name: service.Sevice_Name
            }
            this.selectedServices.push(data)
            this.usersNum= 1;
            this.requirements= ""
        },
        unSelectService(service){
            this.selectedServices = this.selectedServices.filter(s => service.ID !== s.service);
        },
        proceed(){
            let url= this.buildQueryString('/plan-addons', {});
            var data= {
                selectedServices: this.selectedServices,
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
