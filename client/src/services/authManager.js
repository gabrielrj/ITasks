import ajaxCall from "@/services/ajaxCall";
import Cookies from "js-cookie";
import store from "@/store";

export default {
      async login (payload = []) {
            return ajaxCall.callApi('/login', payload);
      },

      userIsLogged(){
            return (Cookies.get('iTasks_auth_token') != null && store.state.userLogged != null)
      }
}
