<script type="text/x-template" id="container-template">
    <div>
        <v-card flat class="pa-4 transparent">
            <v-row>
                <v-col cols="12" class="d-flex">
                    <v-list dense class="transparent py-0">
                        <v-list-item class="px-0">
                            <v-list-item-content>
                                <v-list-item-title class="caption grey--text text--darken-1">Menu</v-list-item-title>
                                <v-list-item-subtitle class="subtitle-1 font-weight-bold grey--text text--darken-4"><?php echo $page_name; ?></v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-col>
                <v-col col="12">
                    <v-card tile flat class="transparent">
                        <v-card-title class="px-0">
                            <v-row class="px-1">
                                <v-col class="px-2" style="min-width: 165px; max-width: 165px;">
                                    <v-btn rounded block depressed class="text-none white--text text--lighten-3" color="#009d39" @click="dialog.add = true">
                                        <v-icon left>mdi-plus</v-icon>Tambah Member
                                    </v-btn>
                                </v-col>

                                <v-spacer></v-spacer>

                                <v-col class="px-2" style="min-width: 454px; max-width: 454px;">
                                    <v-text-field
                                    color="#009d39"
                                    prepend-inner-icon="mdi-magnify" 
                                    rounded dense placeholder="Cari..." 
                                    type="text" 
                                    v-model="search" 
                                    solo 
                                    class="elevation-0" 
                                    background-color="#EEEEEE" 
                                    flat 
                                    hide-details
                                    clear-icon="mdi-backspace"
                                    clearable
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-card-title>
                        <v-card-text class="pa-0">
                            <v-data-table
                                v-model="selected"
                                :headers="headers"
                                :items="dataGrid"
                                :loading="loading"
                                :options.sync="options"
                                :server-items-length="totalData"
                                loading-text="Sedang memuat"
                                :no-data-text="filterOn ? 'Data tidak ditemukan' : 'Data belum ada'"
                                no-results-text="Data belum ada"
                                sort-by-text="Urutkan berdasarkan"
                                :items-per-page="10"
                                height="calc(100vh - 276px)"
                                fixed-header
                                :footer-props="footerProps"
                                item-key="admin_id"
                                flat
                                class="elevation-0 rounded-lg"
                            >
                                <template v-slot:item.action="{ item }">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn v-bind="attrs" v-on="on" icon color="#009d39" class="mx-1" @click="openDialogEdit(item)"><v-icon>mdi-square-edit-outline</v-icon></v-btn>
                                        </template>
                                        <span>Ubah</span>
                                    </v-tooltip>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn v-bind="attrs" v-on="on" icon color="#e74c3c" class="mx-1" @click="openDialogRemove(item)"><v-icon>mdi-delete</v-icon></v-btn>
                                        </template>
                                        <span>Hapus</span>
                                    </v-tooltip>
                                </template>

                                <template v-slot:item.member_name="{ item }">
                                    <v-list-item class="px-0">
                                        <v-list-item-avatar rounded size="50">
                                            <v-img :src="item.member_image_url" contain></v-img>
                                        </v-list-item-avatar>

                                        <v-list-item-content>
                                            <v-list-item-title class="subtitle-2 grey--text text--darken-4">{{ item.member_name }}</v-list-item-title>
                                            <v-list-item-subtitle class="caption grey--text text--darken-1">{{ item.member_email }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                    </v-list-item>
                                </template>

                                <template v-slot:item.member_birth_date="{ item }">
                                    {{ item.member_birth_date | moment("DD MMMM YYYY") }}
                                </template>

                                <template v-slot:item.member_last_login_datetime="{ item }">
                                    {{ item.member_last_login_datetime | moment("DD MMMM YYYY HH:mm") }}
                                </template>

                                <template v-slot:footer.page-text="props">
                                    Menampilkan {{ props.pageStart }} - {{ props.pageStop }} data dari total {{ props.itemsLength }} data
                                    <v-btn
                                        text
                                        class="ml-4 text-none grey--text text--darken-1"
                                        small
                                        @click="fetchGrid"
                                    ><v-icon left>mdi-sync</v-icon> Perbarui</v-btn>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-card>

        <v-dialog v-model="dialog.add" persistent scrollable max-width="450" transition="slide-x-reverse-transition" content-class="my-custom-dialog">
            <v-card tile>
                <v-card-title class="subtitle-1 px-4 pt-4" style="color: #009d39">Tambah Data User</v-card-title>
                <v-card-subtitle class="caption grey--text text--darken-1 px-4 py-1">Lengkapi form dibawah ini</v-card-subtitle>
                <v-divider></v-divider>
                <v-card-text style="height: calc(100vh - 120px);" class="py-4 px-4 my-custom-scroll">

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

                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions class="justify-space-between px-4">
                    <v-btn rounded depressed class="text-none white--text text--lighten-3" color="#009d39"  width="280" @click="doAdd" :loading="loading">
                        <v-icon left>mdi-check-circle-outline</v-icon>Tambahkan User
                    </v-btn>
                    <v-btn rounded depressed outlined class="text-none" color="#009d39" width="130" @click="closeDialog('add')" :disabled="loading">Batal</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialog.edit" persistent scrollable max-width="450" transition="slide-x-reverse-transition" content-class="my-custom-dialog">
            <v-card tile>
                <v-card-title class="subtitle-1 px-4 pt-4" style="color: #009d39">Ubah Data User</v-card-title>
                <v-card-subtitle class="caption grey--text text--darken-1 px-4 py-1">Lengkapi form dibawah ini</v-card-subtitle>
                <v-divider></v-divider>
                <v-card-text style="height: calc(100vh - 120px);" class="py-4 px-4 my-custom-scroll">

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

                <ValidationObserver ref="validation-edit">
                    <v-form @keyup.native.enter="doEdit">
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

                        <div class="subtitle-2 font-weight-bold mt-4">Password <small><i>*kosongkan jika tidak dirubah</i></small></div>
                        <ValidationProvider ref="form-member_password" name="Password" rules="min:8|max:50" v-slot="{ errors }" vid="password">
                            <v-text-field color="#009d39" :type="passwordShow.set ? 'text' : 'password'" dense v-model="form.member_password" counter maxlength="50" placeholder="Tuliskan disini" :append-icon="passwordShow.set ? 'mdi-eye' : 'mdi-eye-off'" @click:append="passwordShow.set = !passwordShow.set" autocomplete="new-password" :error-messages="errors"></v-text-field>
                        </ValidationProvider>

                        <div class="subtitle-2 font-weight-bold mt-4">Ulangi Password <small><i>*kosongkan jika tidak dirubah</i></small></div>
                        <ValidationProvider ref="form-member_repassword" name="Ulangi Password" rules="min:8|max:50|confirmed:password" v-slot="{ errors }">
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

                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions class="justify-space-between px-4">
                    <v-btn rounded depressed class="text-none white--text text--lighten-3" color="#009d39"  width="280" @click="doEdit" :loading="loading">
                        <v-icon left>mdi-check-circle-outline</v-icon>Simpan Perubahan
                    </v-btn>
                    <v-btn rounded depressed outlined class="text-none" color="#009d39" width="130" @click="closeDialog('edit')" :disabled="loading">Batal</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialog.remove" max-width="350">
            <v-card class="rounded-lg">
                <v-card-title class="subtitle-1 px-4">
                    Konfirmasi Hapus
                    <v-spacer></v-spacer>
                    <v-btn icon @click="closeDialog('remove')">
                        <v-icon color="#e74c3c">mdi-close-circle</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text class="text-center" style="height: 100px;">
                    <div class="subtitle-2 fill-height d-flex justify-center align-center">Apakah Anda yakin ingin menghapus member <br> {{ form.member_name }}?</div>
                </v-card-text>
                <v-card-actions class="d-flex">
                    <v-btn rounded depressed width="210" class="text-none white--text text--lighten-3" color="#009d39" @click="doRemove">
                        <v-icon left>mdi-check-circle-outline</v-icon>Ya, yakin
                    </v-btn>
                    <v-btn rounded depressed width="100" class="text-none" outlined color="#009d39" @click="closeDialog('remove')">Tidak</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

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
    </div>
</script>

<script>
    Container = {
        vuetify: new Vuetify(),
        data() {
            return {
                loading: false,
                debounce: null,
                snackbar: {
                    show: false,
                    text: ""
                },
                passwordShow: {
                    set: false,
                    re: false
                },
                dialog: {
                    add: false,
                    edit: false,
                    remove: false
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
                search: null,
                selected: [],
                headers: [{
                        text: 'Aksi',
                        align: 'center',
                        sortable: false,
                        value: 'action',
                        divider: true,
                    },
                    {
                        text: 'Nama',
                        align: 'start',
                        value: 'member_name',
                        divider: true,
                    },
                    {
                        text: 'No. Handphone',
                        align: 'start',
                        value: 'member_mobile_phone',
                        divider: true,
                    },
                    {
                        text: 'Tgl. Lahir',
                        align: 'center',
                        value: 'member_birth_date',
                        divider: true,
                    },
                    {
                        text: 'Jenis Kelamin',
                        align: 'center',
                        value: 'member_gender',
                        divider: true,
                    },
                    {
                        text: 'No. KTP',
                        align: 'start',
                        value: 'member_id_number',
                        divider: true,
                    },
                    {
                        text: 'Login Terakhir',
                        align: 'center',
                        value: 'member_last_login_datetime',
                    },
                ],
                dataGrid: [],
                options: {},
                totalData: 0,
                filterOn: false,
                footerProps: {
                    "show-current-page": true,
                    "show-first-last-page": true,
                    "items-per-page-options": [10, 15, 30, 50, 100],
                    "items-per-page-text": "Data per halaman",
                    "page-text": "{0} - {1} dari total {2}",
                },

                activePicker: null,
                menu: false,

                imageUser: "https://media.monsterdev.web.id/2021/10/image/34AD2-1633394142.jpg",
                imageIsLoad: false,
            }
        },

        watch: {
            "options": {
                async handler() {
                    this.loading = true;
                    await this.fetchGrid();
                    this.loading = false;
                },
                deep: true,
            },
            "search"(val) {
                if (this.debounce) clearTimeout(this.debounce);

                this.debounce = setTimeout(async () => {
                    this.loading = true;
                    await this.fetchGrid();
                    this.loading = false;
                }, 800);
            },
            "menu"(val) {
                val && setTimeout(() => (this.activePicker = 'YEAR'))
            },
        },

        mounted() {},

        methods: {
            saveDate(date) {
                this.$refs.menu.save(date)
            },
            async fetchGrid() {
                this.loading = true;
                this.filterOn = false;

                let sort = "";
                let dir = "";

                if (this.options.sortBy && this.options.sortBy.length > 0) {
                    sort = this.options.sortBy[0];
                } else {
                    sort = "";
                }

                if (this.options.sortDesc && this.options.sortDesc.length > 0) {
                    if (this.options.sortDesc[0]) {
                        dir = "DESC";
                    } else {
                        dir = "ASC";
                    }
                } else {
                    dir = "";
                }

                let params = {
                    page: this.options.page,
                    limit: this.options.itemsPerPage,
                    sort,
                    dir,
                    search: this.search,
                };

                await axios
                    .get("<?php echo site_url('admin/members/get_data'); ?>", {
                        params
                    }).then((response) => {
                        this.loading = false;

                        let res = response.data;

                        if (res.status === 'success') {
                            this.dataGrid = res.data.results;
                            this.totalData = parseInt(res.data.pagination.totalData);
                        } else {
                            this.snackbar.show = true;
                            this.snackbar.text = "Gagal memuat data";
                        }

                    }).catch(function(error) {
                        this.loading = false;
                        this.snackbar.show = true;
                        this.snackbar.text = "Terjadi kesalahan server";
                        console.log(error);
                    });
            },

            async doAdd() {
                const isValid = await this.$refs["validation-add"].validate();

                if (isValid) {
                    this.loading = true;

                    await axios
                        .post("<?php echo site_url('admin/members/add'); ?>", this.form)
                        .then((response) => {
                            this.$refs["validation-add"].reset();

                            let res = response.data;
                            switch (res.status) {
                                case "success":
                                    this.loading = false;
                                    this.snackbar.show = true;
                                    this.snackbar.text = res.msg;
                                    this.closeDialog('add');
                                    this.fetchGrid();
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

            openDialogEdit(item) {
                Object.assign(this.form, {
                    member_id: item.member_id,
                    member_name: item.member_name,
                    member_password: item.member_password,
                    member_email: item.member_email,
                    member_mobile_phone: item.member_mobile_phone,
                    member_birth_date: item.member_birth_date,
                    member_gender: item.member_gender,
                    member_id_number: item.member_id_number,
                    member_image_url: item.member_image_url,
                });

                this.dialog.edit = true;
            },

            async doEdit() {
                const isValid = await this.$refs["validation-edit"].validate();

                if (isValid) {
                    this.loading = true;

                    await axios
                        .post("<?php echo site_url('admin/members/edit'); ?>", this.form)
                        .then((response) => {
                            this.$refs["validation-edit"].reset();

                            let res = response.data;
                            switch (res.status) {
                                case "success":
                                    this.loading = false;
                                    this.snackbar.show = true;
                                    this.snackbar.text = res.msg;
                                    this.closeDialog('edit');
                                    this.fetchGrid();
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

            openDialogRemove(item) {
                Object.assign(this.form, {
                    member_id: item.member_id,
                    member_name: item.member_name,
                });

                this.dialog.remove = true;
            },

            async doRemove() {
                this.loading = true;

                await axios
                    .post("<?php echo site_url('admin/members/remove'); ?>", this.form)
                    .then((response) => {

                        let res = response.data;
                        switch (res.status) {
                            case "success":
                                this.loading = false;
                                this.snackbar.show = true;
                                this.snackbar.text = res.msg;
                                this.closeDialog('remove');
                                this.fetchGrid();
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
            },

            closeDialog(type) {
                Object.assign(this.form, {
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
                });

                this.dialog[type] = false;
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

    };
</script>