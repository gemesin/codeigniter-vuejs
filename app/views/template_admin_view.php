<!DOCTYPE html>
<html>

<head>
    <title><?php template_echo('title'); ?></title>


    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.5.6/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <link href="<?php echo TEMPLATE_ASSET_URL; ?>css/custom.css" rel="stylesheet">

    <?php if (ENVIRONMENT === 'development') : ?>
        <!-- Vue Js Development -->
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <?php else : ?>
        <!-- Vue Js Production -->
        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
    <?php endif; ?>

    <script src="https://unpkg.com/v-currency-field@3.1.1/dist/v-currency-field.umd.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.5.6/dist/vuetify.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vee-validate@3.2.3/dist/vee-validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vee-validate@3.2.3/dist/rules.umd.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="<?php echo TEMPLATE_ASSET_URL; ?>js/validation.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-moment@4.1.0/dist/vue-moment.min.js"></script>


    <script>
        let ComponentClass, instance, Container;
    </script>
</head>

<body>

    <div id="app"></div>

    <script type="text/x-template" id="main-template">
        <v-app>
            <v-navigation-drawer v-model="drawer" color="#009d39" app stateless>
                <v-sheet class="pa-4 transparent">
                    <v-avatar class="mb-4" size="84">
                        <v-img src="<?php echo site_url('assets/images/Logo_Apotek_K-24.png') ?>" contain></v-img>
                    </v-avatar>
                    <div class="subtitle-1 font-weight-bold white--text text--lighten-3"><?= $_SESSION['admin_name'] ?></div>
                    <div class="caption grey--text text--lighten-3"><?= $_SESSION['admin_email'] ?></div>
                </v-sheet>

                <v-list dense>
                    <v-list-item v-for="[icon, text, url] in links" :key="icon" link color="white" :href="url" :class="text == '<?php echo isset($page_name) ? $page_name : ''; ?>' ? 'v-list-item--active' : ''">
                        <v-list-item-icon>
                            <v-icon color="grey lighten-3">{{ icon }}</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title class="grey--text text--lighten-3">{{ text }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>

                <template v-slot:append>
                    <v-list dense>
                        <v-list-item link href="<?php echo site_url('admin/logout'); ?>">
                            <v-list-item-icon class="white--text">
                                <v-icon color="grey lighten-3">mdi-power-standby</v-icon>
                            </v-list-item-icon>

                            <v-list-item-content>
                                <v-list-item-title class="grey--text text--lighten-3">Sign out</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </template>
            </v-navigation-drawer>

            <v-main style="background-color: #009d39;">
                <v-container class="pa-0" fluid id="container" style="height:100vh; background-color: #F5F5F5;">
                </v-container>
                <v-overlay :value="overlay" absolute>
                    <v-progress-circular indeterminate size="64"></v-progress-circular>
                </v-overlay>
            </v-main>

        </v-app>
    </script>

    <?php template_echo('content'); ?>

    <script>
        const App = {
            template: '#main-template',
            name: "mainView",
            data: () => ({
                overlay: false,
                cards: ['Today', 'Yesterday'],
                drawer: true,
                links: [
                    ['mdi-home', 'Beranda', '<?php echo site_url('admin/dashboard') ?>'],
                    ['mdi-account-star', 'User', '<?php echo site_url('admin/users') ?>'],
                    ['mdi-account-multiple', 'Member', '<?php echo site_url('admin/members') ?>'],
                ],
            }),

            methods: {

            },
        }
    </script>

    <script>
        const VeeValidate = window.VeeValidate;
        const VeeValidateRules = window.VeeValidateRules;

        const ValidationProvider = VeeValidate.ValidationProvider;
        const ValidationObserver = VeeValidate.ValidationObserver;

        const translateID = {
            "code": "id",
            "messages": {
                "alpha": "{_field_} hanya boleh mengandung karakter alfabet",
                "alpha_num": "{_field_} hanya boleh mengandung karakter alfanumerik",
                "alpha_dash": "{_field_} boleh mengandung karakter alfanumerik, tanda hubung, dan garis bawah",
                "alpha_spaces": "{_field_} hanya boleh berisi karakter alfabet serta spasi",
                "between": "{_field_} harus di antara {min} dan {max}",
                "confirmed": "{_field_} tidak cocok dengan {target}",
                "digits": "{_field_} harus berupa {length} digit angka",
                "dimensions": "{_field_} harus berdimensi lebar {width} pixel dan tinggi {height} pixel",
                "email": "{_field_} harus berupa alamat surel yang benar",
                "excluded": "{_field_} harus berupa nilai yang sah",
                "ext": "{_field_} harus berupa berkas yang benar",
                "image": "{_field_} harus berupa gambar",
                "integer": "{_field_} harus berupa bilangan bulat",
                "length": "Panjang {_field_} harus tepat {length}",
                "max_value": "Nilai {_field_} tidak boleh lebih dari {max}",
                "max": "{_field_} tidak boleh lebih dari {length} karakter",
                "mimes": "Tipe berkas {_field_} harus benar",
                "min_value": "Nilai {_field_} tidak boleh kurang dari {min}",
                "min": "{_field_} minimal mengandung {length} karakter",
                "numeric": "{_field_} harus berupa angka",
                "oneOf": "{_field_} harus berupa nilai yang sah",
                "regex": "Format {_field_} salah",
                "required": "{_field_} harus diisi",
                "required_if": "{_field_} harus diisi",
                "size": "{_field_} harus lebih kecil dari {size}KB"
            }
        };

        for (let key in translateID.messages) {
            VeeValidate.extend(key, VeeValidateRules[key]);
        }

        VeeValidate.localize('id', translateID);

        Vue.component('ValidationProvider', ValidationProvider);
        Vue.component('ValidationObserver', ValidationObserver);

        Vue.use(vueMoment)

        new Vue({
            vuetify: new Vuetify(),
            render: h => h(App)
        }).$mount('#app')

        window.onload = function() {
            ComponentClass = Vue.extend({
                template: '#container-template',
            });

            instance = new ComponentClass(Container);
            instance.$mount();
            document.getElementById('container').innerHTML = "";
            document.getElementById('container').appendChild(instance.$el);
        }
    </script>
</body>

</html>