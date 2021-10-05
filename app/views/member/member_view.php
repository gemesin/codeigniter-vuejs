<script type="text/x-template" id="container-template">
    <v-row align="center" justify="center">
    <v-card width="400" class="rounded-xl">
        <v-img
          height="200px"
          src="https://cdn.pixabay.com/photo/2018/08/21/23/29/forest-3622519_960_720.jpg"
        >
          <v-app-bar
            flat
            color="rgba(0, 0, 0, 0)"
          >
            <v-toolbar-title class="text-h6 pl-0">
              Profil Saya
            </v-toolbar-title>

            <v-spacer></v-spacer>

            <v-btn
              color="white"
              text
              href="<?php echo site_url('logout'); ?>"
            >
            Logout <v-icon right>mdi-logout-variant</v-icon>
            </v-btn>
          </v-app-bar>

          <v-card-title class="white--text mt-8">
            <v-avatar size="56">
              <img
                alt="user"
                src="<?= $_SESSION['member_image_url']; ?>"
              >
            </v-avatar>
            <p class="ml-3">
                <?= $_SESSION['member_name']; ?>
                <br>
                <small><?= $_SESSION['member_email']; ?></small>
            </p>
          </v-card-title>
        </v-img>

        <v-card-text>
            <div class="font-weight-bold mb-2">
                Informasi Profil
            </div>

            <v-list-item class="px-0">
                <v-list-item-content>
                    <v-list-item-title class="subtitle-2 grey--text text--darken-4">Jenis Kelamin</v-list-item-title>
                </v-list-item-content>
                <v-list-item-content>
                    <v-list-item-subtitle class="caption grey--text text--darken-1"> <?= $_SESSION['member_gender']; ?></v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
            <v-list-item class="px-0">
                <v-list-item-content>
                    <v-list-item-title class="subtitle-2 grey--text text--darken-4">Tgl. Lahir</v-list-item-title>
                </v-list-item-content>
                <v-list-item-content>
                    <v-list-item-subtitle class="caption grey--text text--darken-1"> <?= convert_date($_SESSION['member_birth_date']); ?></v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
            <v-list-item class="px-0">
                <v-list-item-content>
                    <v-list-item-title class="subtitle-2 grey--text text--darken-4">No. Handphone</v-list-item-title>
                </v-list-item-content>
                <v-list-item-content>
                    <v-list-item-subtitle class="caption grey--text text--darken-1"> <?= $_SESSION['member_mobile_phone']; ?></v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
            <v-list-item class="px-0">
                <v-list-item-content>
                    <v-list-item-title class="subtitle-2 grey--text text--darken-4">No. KTP</v-list-item-title>
                </v-list-item-content>
                <v-list-item-content>
                    <v-list-item-subtitle class="caption grey--text text--darken-1"> <?= $_SESSION['member_id_number']; ?></v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
        </v-card-text>
      </v-card>
    </v-row>
</script>

<script>
    Container = {
        vuetify: new Vuetify(),
        data: () => ({}),
    }
</script>