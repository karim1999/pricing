export const auth = {
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
