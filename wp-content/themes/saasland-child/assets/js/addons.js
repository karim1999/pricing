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
            selectedServices: [],
            pages: 1,
            currentPage: 1,
            numPerPage: 9
        };
    },
    mounted(){
        console.log(this.previousServices)
        this.query= this.getCurrentQueryString()
        this.getData()
    },
    computed: {
        paginatedServices(){
            if(this.services){
                let start= this.numPerPage * (this.currentPage - 1);
                console.log("start", start)
                return this.services.slice(start, start + this.numPerPage)
            }else{
                return []
            }
        }
    },
    methods: {
        async getData() {
            this.isLoading = true;
            this.services= await productAddons.get(this.query.plan_id);
            if(this.services)
                this.pages= Math.ceil(this.services.length / this.numPerPage);
            console.log(this.services);
            this.isLoading = false;
            Vue.nextTick(function () {
                console.log("done")
                jQuery('[data-toggle="tooltip"]').tooltip()
            })
        },
        selectService(service){
            let data= {
                service: service.ID,
                Quantity: this.usersNum,
                Terms: this.requirements,
                Calculation_Type: service.Calculation_Type,
                Add_Ons1: service.ID,
                name: service.Add_Ons
            }
            this.unSelectService(service)
            this.selectedServices.push(data)
            this.usersNum= 1;
            this.requirements= ""
            // jQuery('.modal').modal('hide')
        },
        unSelectService(service){
            this.selectedServices = this.selectedServices.filter(s => service.ID !== s.service)
        },
        editService(service){
            let current = this.selectedServices.filter(s => service.ID === s.service);
            this.requirements= current[0].Terms;
            this.usersNum= current[0].Quantity;
            console.log(service)
            jQuery('#exampleModal'+service.ID).modal()
        },
        openModal(modalId){
            this.usersNum= 1;
            this.requirements= "";
            jQuery(modalId).modal()
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
        },
        truncate(value, limit = 40){
            if (value.length > limit) {
                value = value.substring(0, (limit - 3)) + '...';
            }

            return value
        },
        clickCallback(pageNum) {
            this.currentPage= pageNum
            console.log(pageNum)
        }
    }
});

new Vue({
    el: document.getElementById('vue-container')
});
