const Constants = Object.freeze({
  CLIENT_ID: "1000.FHEYBJ33G2P7SGH3QCLX6SWA3QVEAI",
  CLIENT_SECRET: "c17898d6823702067e5e9f82faf48e1bef0a89da59",
  REFRESH_TOKEN:
      "1000.df232d92e6558c1526a86ab60be6fa07.3ccf6d6eec87f475a721c2404b0ec19a",
  URL:
      "https://karim-cors.herokuapp.com/https://creator.zoho.com/api/v2/gamalearn/swiftassess"
});
const dynamicSort= function (property) {
  var sortOrder = 1;
  if(property[0] === "-") {
    sortOrder = -1;
    property = property.substr(1);
  }
  return function (a,b) {
    /* next line works with strings and numbers,
     * and you may want to customize it to your needs
     */
    var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
    return result * sortOrder;
  }
}
const auth = {
  generateToken() {
    return axios
        .post(
            "https://karim-cors.herokuapp.com/https://accounts.zoho.com/oauth/v2/token",
            {},
            {
              params: {
                refresh_token: Constants.REFRESH_TOKEN,
                client_id: Constants.CLIENT_ID,
                client_secret: Constants.CLIENT_SECRET,
                grant_type: "refresh_token"
              }
            }
        )
        .then(res => {
          if (res.data.access_token) {
            console.log(res.data.access_token);
            this.setToken(res.data.access_token);
          }
          return res.data.access_token;
        })
        .catch(err => {
          console.log(err);
        });
  },
  setToken(cvalue) {
    var d = new Date();
    d.setTime(d.getTime() + 50 * 60 * 1000);
    var expires = "expires=" + d.toUTCString();
    document.cookie = "token=" + cvalue + ";" + expires + ";path=/";
  },
  getToken() {
    var name = "token=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(";");
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == " ") {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return auth.generateToken();
  }
};

const productTypes = {
  async get() {
    return axios
      .get(Constants.URL + "/report/All_Product_Types", {
        headers: {
          Authorization: "Zoho-oauthtoken " + (await auth.getToken())
        }
      })
      .then(res => {
        res.data.data.sort(dynamicSort("Sorting_Number"));
        return res.data.data;
      })
      .catch(err => {
        console.log(err.response);
        if (err.response.status === 401) {
          return productTypes.get(auth.generateToken());
        }
      });
  }
};
const productPlans = {
  async get() {
    return axios
      .get(Constants.URL + "/report/All_Features_Plans", {
        headers: {
          Authorization: "Zoho-oauthtoken " + (await auth.getToken())
        }
      })
      .then(res => {
        res.data.data.sort(dynamicSort("Sorting_Number"));
        return res.data.data;
      })
      .catch(err => {
        console.log(err.response);
        if (err.response.status === 401) {
          return productPlans.get(auth.generateToken());
        }
      });
  },
  async getPlan(planId) {
    return axios
      .get(Constants.URL + "/report/All_Features_Plans/"+planId, {
        headers: {
          Authorization: "Zoho-oauthtoken " + (await auth.getToken())
        }
      })
      .then(res => {
        return res.data.data;
      })
      .catch(err => {
        console.log(err.response);
        if (err.response.status === 401) {
          return productPlans.get(auth.generateToken());
        }
      });
  }
};
const productFeatures = {
  async get(productType = false) {
    let params= {}
    if(productType){
      params= {
        criteria: "Product_Plan.Product_Type="+productType
      }
    }
    return axios
      .get(Constants.URL + "/report/Product_Plan_Features_SF_Report", {
        params,
        headers: {
          Authorization: "Zoho-oauthtoken " + (await auth.getToken())
        }
      })
      .then(res => {
        res.data.data.sort(dynamicSort("Sub_Feature.Sorting_Number"));
        res.data.data.sort(dynamicSort("Main_Feature.Sorting_Number"));
        return res.data.data;
      })
      .catch(err => {
        console.log(err.response);
        if (err.response.status === 401) {
          return productPlans.get(auth.generateToken());
        }
      });
  }
};
const productPackages = {
  async get() {
    return axios
      .get(Constants.URL + "/report/All_Support_Packages", {
        headers: {
          Authorization: "Zoho-oauthtoken " + (await auth.getToken())
        }
      })
      .then(res => {
        return res.data.data;
      })
      .catch(err => {
        console.log(err.response);
        if (err.response.status === 401) {
          return productPlans.get(auth.generateToken());
        }
      });
  },
  async features() {
    return axios
      .get(Constants.URL + "/report/Support_Package_Features_SF_Report", {
        headers: {
          Authorization: "Zoho-oauthtoken " + (await auth.getToken())
        }
      })
      .then(res => {
        res.data.data.sort(dynamicSort("Support_Packages_Feature.Sorting_Number"));
        return res.data.data;
      })
      .catch(err => {
        console.log(err.response);
        if (err.response.status === 401) {
          return productPlans.get(auth.generateToken());
        }
      });
  }
};
const productServices = {
  async get() {
    return axios
      .get(Constants.URL + "/report/All_Professional_Services", {
        // params: {
        //   criteria: "Add_Product_Plans_Product_Plan=" + productPlan
        // },
        headers: {
          Authorization: "Zoho-oauthtoken " + (await auth.getToken())
        }
      })
      .then(res => {
        return res.data.data;
      })
      .catch(err => {
        console.log(err.response);
        if (err.response.status === 401) {
          return productPlans.get(auth.generateToken());
        }
      });
  }
};
const productAddons = {
  async get(productPlan) {
    return axios
      .get(Constants.URL + "/report/All_Add_Ons_Sf", {
        params: {
          criteria: "Add_Product_Plans_Product_Plan=" + productPlan
        },
        headers: {
          Authorization: "Zoho-oauthtoken " + (await auth.getToken())
        }
      })
      .then(res => {
        return res.data.data;
      })
      .catch(err => {
        console.log(err.response);
        if (err.response.status === 401) {
          return productPlans.get(auth.generateToken());
        }
      });
  }
};
const Quote = {
  async create(data) {
    return axios
      .post(Constants.URL + "/form/Create_Quote", {
        data
      }, {
        headers: {
          Authorization: "Zoho-oauthtoken " + (await auth.getToken()),
        }
      })
      .then(res => {
        return res.data.data.ID;
      })
      .catch(err => {
        console.log(err.response);
        if (err.response.status === 401) {
          return productPlans.get(auth.generateToken());
        }
      });
  },
};
const productInstitution = {
  async get() {
    return axios
      .get(Constants.URL + "/report/All_Institution_Types", {
        headers: {
          Authorization: "Zoho-oauthtoken " + (await auth.getToken()),
        }
      })
      .then(res => {
        return res.data.data;
      })
      .catch(err => {
        console.log(err.response);
        if (err.response.status === 401) {
          return productPlans.get(auth.generateToken());
        }
      });
  },
};
const productTrainingType = {
  async get() {
    return axios
      .get(Constants.URL + "/report/All_Training_Vouchers", {
        headers: {
          Authorization: "Zoho-oauthtoken " + (await auth.getToken()),
        }
      })
      .then(res => {
        return res.data.data;
      })
      .catch(err => {
        console.log(err.response);
        if (err.response.status === 401) {
          return productPlans.get(auth.generateToken());
        }
      });
  },
};
// const Hostings = {
//   async get() {
//     return axios
//       .get(Constants.URL + "/report/All_Institution_Types", {
//         headers: {
//           Authorization: "Zoho-oauthtoken " + (await auth.getToken()),
//         }
//       })
//       .then(res => {
//         return res.data.data;
//       })
//       .catch(err => {
//         console.log(err.response);
//         if (err.response.status === 401) {
//           return productPlans.get(auth.generateToken());
//         }
//       });
//   },
// };
