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
                    Selamat datang, <b><?= $_SESSION['admin_name'] ?></b>
                    <br>
                    <small>Terakhir login <?= convert_datetime($_SESSION['admin_last_login_datetime']) ?></small>
                </v-col>
            </v-row>
        </v-card>
    </div>
</script>

<script>
    Container = {
        vuetify: new Vuetify(),
        data() {
            return {}
        }

    };
</script>