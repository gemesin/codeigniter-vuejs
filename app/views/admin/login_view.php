<script type="text/x-template" id="container-template">
  <v-row align="center" justify="center">
      <v-card width="400" class="transparent" flat>
        <v-card-title class="title font-weight-bold justify-center pt-8" style="color: #009d39;">LOGIN ADMINISTRATOR</v-card-title>
        <v-card-text>
          <v-row align="center" justify="center" style="height: calc(100vh - 580px);">
            <v-img src="<?php echo site_url('assets/images/Logo_Apotek_K-24.png') ?>" contain max-width="200"></v-img>
          </v-row>
        </v-card-text>
        <v-card-text class="pa-0 elevation-1 rounded-xl">
            <v-card tile flat class="rounded-xl">
              <v-card-title class="justify-center font-weight-bold grey--text text--darken-2">Login</v-card-title>
              <v-card-text>
                <ValidationObserver ref="validation-login">
                  <v-form @keyup.native.enter="doLogin">
                    <ValidationProvider
                      ref="form-username"
                      name="Username"
                      rules="required|min:6"
                      v-slot="{ errors }"
                    >
                      <v-text-field
                        rounded
                        dense
                        color="#009d39"
                        placeholder="Username"
                        type="text"
                        v-model="form.username"
                        :error-messages="errors"
                        solo
                        :disabled="loading"
                        :loading="loading"
                        autofocus
                        class="elevation-0"
                        background-color="#f5f5f5"
                        autocomplete="off"
                        flat
                      ></v-text-field>
                    </ValidationProvider>
                    <ValidationProvider
                      ref="form-password"
                      name="Password"
                      rules="required|min:8"
                      v-slot="{ errors }"
                    >
                      <v-text-field
                        rounded
                        dense
                        color="#009d39"
                        placeholder="Password"
                        :type="showPassword ? 'text' : 'password'"
                        v-model="form.password"
                        :error-messages="errors"
                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        @click:append="showPassword = !showPassword"
                        solo
                        :disabled="loading"
                        :loading="loading"
                        background-color="#f5f5f5"
                        flat
                        autocomplete="new-password"
                      ></v-text-field>
                    </ValidationProvider>
                  </v-form>
                </ValidationObserver>
                <v-btn
                  rounded
                  color="#009d39"
                  depressed
                  block
                  :loading="loading"
                  :disabled="loading"
                  class="text-none white--text text--lighten-3 my-2"
                  @click="doLogin"
                >
                  Login
                </v-btn>
              </v-card-text>
            </v-card>
        </v-card-text>
      </v-card>

      <v-snackbar v-model="snackbar.show">
          {{ snackbar.text }}

          <template v-slot:action="{ attrs }">
              <v-btn
                  color="#f1c40f"
                  text
                  v-bind="attrs"
                  @click="snackbar.show = false"
              >
              OKE
              </v-btn>
          </template>
      </v-snackbar>
    </v-row>
</script>

<script>
  Container = {
    vuetify: new Vuetify(),
    data: () => ({
      loading: false,
      snackbar: {
        show: false,
        text: ""
      },
      form: {
        username: null,
        password: null,
      },
      showPassword: false,
      feedBack: {
        global: null,
        form: {
          username: null,
          password: null,
        },
      },
    }),

    methods: {
      async doLogin() {
        const isValid = await this.$refs["validation-login"].validate();

        if (isValid) {

          this.loading = true;

          await axios
            .post("<?php echo site_url('admin/login/verify'); ?>", this.form)
            .then((response) => {
              this.$refs["validation-login"].reset();

              let res = response.data;
              switch (res.status) {
                case "success":
                  this.snackbar.text = "Login berhasil! Mohon tunggu sebentar...";
                  window.location = '../admin/' + res.data.redirect;
                  break;
                case "validation":
                  this.loading = false;
                  this.snackbar.show = true;
                  this.snackbar.text = res.msg;

                  if (Object.keys(res.error_data).length > 0) {
                    Object.keys(res.error_data).forEach(item => {
                      this.$refs[`form-${item}`].applyResult({
                        errors: [res.error_data[item]],
                        valid: false,
                        failedRules: {},
                      });
                    });
                  }
                  break;
                case "failed":
                  this.loading = false;
                  this.snackbar.show = true;
                  this.snackbar.text = res.msg;
                  break;
              }
            })
            .catch(function(error) {
              this.loading = false;
              this.snackbar.show = true;
              this.snackbar.text = "Terjadi kesalahan server";
              console.log(error);
            });
        }
      },
    },
  }
</script>