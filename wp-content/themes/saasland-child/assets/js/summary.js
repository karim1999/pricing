Vue.component('form1', {
    props: ['previousData'],
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
            required: "Yes",
            institution: "",
            licenses: 1,
            vouchers: 1,
            teachers: 0,
            students: 0,
            firstName: "",
            lastName: "",
            institutionName: "",
            institutionType: "",
            Monthly_Yearly: "Monthly",
            phone: "",
            email: "",
            isLoading: false,
            training_type: "",
            n_users_training: 0,
            package: "",
            package_name: "",
        };
    },
    mounted(){
        console.log(this.previousData)
        let uri = window.location.search.substring(1);
        let params = new URLSearchParams(uri);
        this.plan_id = params.get("plan_id");
        this.institution = params.get("institution");
        this.plan = params.get("plan");
        this.product_type_id = params.get("product_type_id");
        this.product_type = params.get("product_type");
        this.licenses = params.get("licenses");
        this.vouchers = params.get("vouchers");
        this.training_type = params.get("training_type");
        this.n_users_training = params.get("n_users_training");
        this.package = params.get("package");
        this.email = params.get("email");
        this.firstName = params.get("firstName");
        this.lastName = params.get("lastName");
        this.phone = params.get("phone");
        this.package_name = params.get("package_name");
        this.institutionName = params.get("institutionName");
        this.institutionType = params.get("institutionType");
        this.teachers = params.get("teachers");
        this.students = params.get("students");
        this.Monthly_Yearly = params.get("Monthly_Yearly");
    },
    methods: {
        async proceed(){
            this.isLoading= true
            console.log("proceeding")
            if(this.firstName && this.lastName){
                let quoteData= {
                    "Institution_Type": this.institution,
                    "Product_Type": this.product_type_id,
                    "Features_Plan": this.plan_id,
                    // "Location": "4046528000000040095",
                    "Does_the_Contact_is_already_Exist_in_Zoho_CRM": "Not Exists",
                    "Company_Name": this.firstName + " " + this.lastName,
                    "Phone_Number": this.phone,
                    "Contact_Person_Name": {
                        "first_name1": this.firstName,
                        "last_name1": this.lastName
                    },
                    "Email": this.email,
                    "Professional_Services_CQ": this.previousData.selectedServices,
                    "Add_On_Package": this.previousData.selectedAddons,
                    "Required_User_Licenses": this.licenses,
                    "Support_Package": this.package
                }
                if(this.vouchers){
                    quoteData.Required_Test_Vouchers= this.vouchers;
                }
                if(this.training_type){
                    quoteData.Training_Package= this.training_type;
                }
                if(this.No_of_Admins_Teachers){
                    quoteData.No_of_Admins_Teachers= this.teachers;
                }
                if(this.No_of_Students){
                    quoteData.No_of_Students= this.students;
                }
                if(this.Monthly_Yearly){
                    quoteData.Monthly_Yearly= this.Monthly_Yearly;
                }
                let quoteId= await Quote.create(quoteData)
                console.log(quoteId)
                window.location.href= "/thank-you"
            }else {
                alert("Please fill the required information")
            }
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
