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