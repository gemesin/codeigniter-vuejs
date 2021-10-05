<script type="text/x-template" id="container-template">
  <v-row align="center" justify="center">
      <v-card width="400" class="transparent" flat>
        <v-card-title class="title font-weight-bold justify-center pt-8" style="color: #009d39;">REGISTRASI MEMBER</v-card-title>
        <v-card-text class="pa-0 elevation-1 rounded-xl mb-6">
            <v-card tile flat class="rounded-xl">
              <v-card-text style="height: calc(100vh - 160px); overflow-y: auto">
                <ValidationObserver ref="validation-register">
                  <v-form @keyup.native.enter="doRegister">
                    <div class="pt-2">
                        <v-row>
                        <v-col cols="12">
                            <v-hover v-slot="{ hover }">
                            <v-card flat>
                                <v-card-text class="pa-0">
                                <v-avatar tile class="rounded-lg mx-auto d-block" size="92.5" color="rgb(245, 245, 245)">
                                    <v-img :src="form.member_image_url" contain @loadstart="imageIsLoad = true;" @load="imageIsLoad = false;">
                                    <v-row
                                        class="fill-height ma-0"
                                        align="center"
                                        justify="center"
                                    >
                                        <v-progress-circular
                                        v-show="imageIsLoad || loading"
                                        :indeterminate="imageIsLoad || loading"
                                        color="#64B5F6"
                                        ></v-progress-circular>
                                    </v-row>
                                    <v-expand-transition>
                                        <div
                                        v-if="hover"
                                        class="d-flex transition-fast-in-fast-out grey lighten-3 v-card--reveal white--text"
                                        style="height: 92.5px; width: 92.5px; border-radius: 5px;"
                                        >
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn v-bind="attrs" v-on="on" small icon color="#009d39" @click="$refs.uploader.click()" :disabled="loading"><v-icon small>mdi-image-search</v-icon></v-btn>
                                            </template>
                                            <span>Ganti gambar</span>
                                        </v-tooltip>
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn v-bind="attrs" v-on="on" small icon color="#e74c3c" :disabled="loading" @click="form.member_image_url = imageUser"><v-icon small>mdi-delete</v-icon></v-btn>
                                            </template>
                                            <span>Hapus gambar</span>
                                        </v-tooltip>
                                        </div>
                                    </v-expand-transition>
                                    <input
                                        ref="uploader"
                                        class="d-none"
                                        type="file"
                                        accept="image/png, image/jpeg, image/jpg"
                                        @change="onFileChanged"
                                    >
                                    </v-img>
                                </v-avatar>
                                </v-card-text>
                            </v-card>
                            </v-hover>
                        </v-col>
                        </v-row>
                    </div>

                    <ValidationObserver ref="validation-add">
                        <v-form @keyup.native.enter="doAdd">
                            <div class="subtitle-2 font-weight-bold mt-4">Nama <small style="color: #e74c3c;"><i>*wajib diisi</i></small></div>
                            <ValidationProvider ref="form-member_name" name="Nama" rules="required|max:50" v-slot="{ errors }">
                                <v-text-field color="#009d39" dense v-model="form.member_name" counter maxlength="50" placeholder="Tuliskan disini" :loading="loading" :error-messages="errors"></v-text-field>
                            </ValidationProvider>

                            <div class="subtitle-2 font-weight-bold mt-4">Jenis Kelamin <small style="color: #e74c3c;"><i>*wajib diisi</i></small></div>
                            <div class="d-flex justify-space-between" style="width: 300px;">
                                <ValidationProvider ref="form-member_gender" name="Jenis Kelamin" rules="required" v-slot="{ errors }">
                                    <v-radio-group v-model="form.member_gender" row :error-messages="errors">
                                        <v-radio color="#009d39" label="Laki-laki" value="Laki-laki"></v-radio>
                                        <v-radio color="#009d39" label="Perempuan" value="Perempuan"></v-radio>
                                    </v-radio-group>
                                </ValidationProvider>
                            </div>

                            <div class="subtitle-2 font-weight-bold mt-4">Tgl. Lahir <small style="color: #e74c3c;"><i>*wajib diisi</i></small></div>
                            <v-menu
                                ref="menu"
                                v-model="menu"
                                :close-on-content-click="false"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <ValidationProvider ref="form-member_birth_date" name="Tgl. Lahir" rules="required" v-slot="{ errors }">
                                        <v-text-field
                                            color="#009d39"
                                            dense
                                            v-model="form.member_birth_date"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            placeholder="Tuliskan disini" 
                                            :loading="loading" 
                                            :error-messages="errors"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </template>
                                <v-date-picker
                                    color="#009d39"
                                    v-model="form.member_birth_date"
                                    :active-picker.sync="activePicker"
                                    :max="(new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)"
                                    min="1950-01-01"
                                    @change="saveDate"
                                ></v-date-picker>
                            </v-menu>

                            <div class="subtitle-2 font-weight-bold mt-4">Email <small style="color: #e74c3c;"><i>*wajib diisi</i></small></div>
                            <ValidationProvider ref="form-member_email" name="Email" rules="required|max:255|email" v-slot="{ errors }">
                                <v-text-field color="#009d39" dense v-model="form.member_email" counter maxlength="255" placeholder="Tuliskan disini" :loading="loading" :error-messages="errors"></v-text-field>
                            </ValidationProvider>

                            <div class="subtitle-2 font-weight-bold mt-4">Password <small style="color: #e74c3c;"><i>*wajib diisi</i></small></div>
                            <ValidationProvider ref="form-member_password" name="Password" rules="required|min:8|max:50" v-slot="{ errors }" vid="password">
                                <v-text-field color="#009d39" :type="passwordShow.set ? 'text' : 'password'" dense v-model="form.member_password" counter maxlength="50" placeholder="Tuliskan disini" :append-icon="passwordShow.set ? 'mdi-eye' : 'mdi-eye-off'" @click:append="passwordShow.set = !passwordShow.set" autocomplete="new-password" :error-messages="errors"></v-text-field>
                            </ValidationProvider>

                            <div class="subtitle-2 font-weight-bold mt-4">Ulangi Password <small style="color: #e74c3c;"><i>*wajib diisi</i></small></div>
                            <ValidationProvider ref="form-member_repassword" name="Ulangi Password" rules="required|min:8|max:50|confirmed:password" v-slot="{ errors }">
                                <v-text-field color="#009d39" :type="passwordShow.re ? 'text' : 'password'" dense v-model="form.member_repassword" counter maxlength="50" placeholder="Tuliskan disini" :append-icon="passwordShow.re ? 'mdi-eye' : 'mdi-eye-off'" @click:append="passwordShow.re = !passwordShow.re" autocomplete="new-password" :error-messages="errors"></v-text-field>
                            </ValidationProvider>

                            <div class="subtitle-2 font-weight-bold mt-4">No. Handphone <small style="color: #e74c3c;"><i>*wajib diisi</i></small></div>
                            <ValidationProvider ref="form-member_mobile_phone" name="No. Handphone" rules="required|max:20|alpha_dash" v-slot="{ errors }">
                                <v-text-field color="#009d39" dense v-model="form.member_mobile_phone" counter maxlength="20" placeholder="Tuliskan disini" :loading="loading" :error-messages="errors"></v-text-field>
                            </ValidationProvider>

                            <div class="subtitle-2 font-weight-bold mt-4">No. KTP <small style="color: #e74c3c;"><i>*wajib diisi</i></small></div>
                            <ValidationProvider ref="form-member_id_number" name="No. KTP" rules="required|max:30|alpha_dash" v-slot="{ errors }">
                                <v-text-field color="#009d39" dense v-model="form.member_id_number" counter maxlength="30" placeholder="Tuliskan disini" :loading="loading" :error-messages="errors"></v-text-field>
                            </ValidationProvider>
                        </v-form>
                    </ValidationObserver>
                  </v-form>
                </ValidationObserver>
              </v-card-text>
              <v-card-actions>
                <v-btn
                  rounded
                  color="#009d39"
                  depressed
                  :loading="loading"
                  :disabled="loading"
                  class="text-none white--text text--lighten-3 my-2 px-8"
                  @click="doRegister"
                >
                  Register
                </v-btn>
                <v-spacer></v-spacer>
                <div class="text-right">
                    Sudah punya akun?
                    <v-btn
                        small
                        color="#009d39"
                        depressed
                        text
                        :loading="loading"
                        :disabled="loading"
                        class="text-none my-2"
                        href="<?php echo site_url('login'); ?>"
                    >Login</v-btn>
                </div>
              </v-card-actions>
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
        member_id: null,
        member_name: null,
        member_password: null,
        member_repassword: null,
        member_email: null,
        member_mobile_phone: null,
        member_birth_date: null,
        member_gender: null,
        member_id_number: null,
        member_image_url: "https://media.monsterdev.web.id/2021/10/image/34AD2-1633394142.jpg",
      },
      passwordShow: {
        set: false,
        re: false
      },

      activePicker: null,
      menu: false,

      imageUser: "https://media.monsterdev.web.id/2021/10/image/34AD2-1633394142.jpg",
      imageIsLoad: false,
    }),

    watch: {
      "menu"(val) {
        val && setTimeout(() => (this.activePicker = 'YEAR'))
      },
    },

    methods: {
      saveDate(date) {
        this.$refs.menu.save(date)
      },
      async doRegister() {
        const isValid = await this.$refs["validation-register"].validate();

        if (isValid) {

          this.loading = true;

          await axios
            .post("<?php echo site_url('register/save'); ?>", this.form)
            .then((response) => {
              this.$refs["validation-register"].reset();

              let res = response.data;
              switch (res.status) {
                case "success":
                  this.snackbar.text = "Registrasi berhasil! Mohon tunggu sebentar...";
                  window.location = 'login';
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

      async onFileChanged(e) {
        if (e.target.files.length > 0) {
          let file = e.target.files[0];

          let formData = new FormData()
          formData.append("key", "image")
          formData.append("image", file)

          this.loading = true;

          await fetch('https://media.monsterdev.web.id/image.php', {
              method: 'POST',
              body: formData
            })
            .then((response) => response.json())
            .then((responseData) => {
              this.loading = false;
              if (responseData.status == 200) {
                this.form.member_image_url = responseData.data.fileuri;
              } else {
                alert(responseData.message);
              }
            })
            .catch((error) => {
              this.loading = false;
              alert('Terjadi Kesalahan Server');
            });
        }
      },
    },
  }
</script>