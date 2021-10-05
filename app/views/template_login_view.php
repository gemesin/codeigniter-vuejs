<!DOCTYPE html>
<html>

<head>
    <title><?php template_echo('title'); ?> | Administrator</title>


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

    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.5.6/dist/vuetify.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vee-validate@3.2.3/dist/vee-validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vee-validate@3.2.3/dist/rules.umd.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="<?php echo TEMPLATE_ASSET_URL; ?>js/validation.js"></script>

    <script>
        let ComponentClass, instance, Container;
    </script>
</head>

<body>

    <div id="app"></div>

    <script type="text/x-template" id="main-template">
        <v-app>
            <v-container fluid class="fill-height" id="container" style="background-color: #f2f2f2;"></v-container>
        </v-app>
    </script>

    <?php template_echo('content'); ?>

    <script>
        const App = {
            template: '#main-template',
            name: "mainView",
            data: () => ({

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