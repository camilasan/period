<!--
 - @copyright Copyright (c) 2024 Camila Ayres <hello@camilasan.com>
 -
 - @author Camila Ayres <hello@camilasan.com>
 -
 - @license GNU AGPL version 3 or any later version
 -
 - This program is free software: you can redistribute it and/or modify
 - it under the terms of the GNU Affero General Public License as
 - published by the Free Software Foundation, either version 3 of the
 - License, or (at your option) any later version.
 -
 - This program is distributed in the hope that it will be useful,
 - but WITHOUT ANY WARRANTY; without even the implied warranty of
 - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 - GNU Affero General Public License for more details.
 -
 - You should have received a copy of the GNU Affero General Public License
 - along with this program. If not, see <http://www.gnu.org/licenses/>.
 -
 -->
<template>
  <NcContent app-name="period">
      <NcAppNavigation>
        <NcAppNavigationNew v-if="!loading"
          :text="t('period profile', 'Add profile')"
          :disabled="false"
          button-id="add-profile-button"
          button-class="icon-add"
          @click="newProfile" />
        <ul>
          <NcAppNavigationItem v-for="profile in profiles"
            :key="profile.id"
            :name="profile.name ? profile.name : t('period profile', 'Add profile')"
            :class="{active: currentProfileId === profile.id}"
            @click="openProfile(profile)">
            <template #icon>
              <Pencil :size="20" />
            </template>
            <template #actions>
              <NcActionButton @click="deleteProfile(profile)">
                <template #icon>
                  <Delete :size="20" />
                </template>
                {{ t('period profile', 'Delete profile') }}
              </NcActionButton>
            </template>
          </NcAppNavigationItem>
        </ul>
      </NcAppNavigation>
      <NcAppContent>
        <div v-if="currentProfile" class="profile-content">
          <h2>{{t('period profile', 'Profile')}}: {{ currentProfile.name }}</h2>
          <div class="form">
            <label>Name</label>
            <input ref="name"
                   v-model="currentProfile.name"
                   :disabled="updating"/>
            <label>Age</label>
            <input ref="age"
              v-model="currentProfile.age"
              type="number"
              :disabled="updating">
            <label>Contraceptive</label>
            <input ref="contraceptive"
                   v-model="currentProfile.contraceptive"
                   :disabled="updating"/>
            <input type="button"
              class="primary"
              :value="t('period profile', 'Save')"
              :disabled="updating || !savePossible"
              @click="saveProfile">
          </div>
        </div>
        <NcEmptyContent v-if="isEmpty" key="empty">
          <template #icon>
            <Plus :size="20" />
          </template>
          <template #title>
            {{ t('period profile', 'Create a profile to get started') }}
          </template>
        </NcEmptyContent>
      </NcAppContent>
  </NcContent>
</template>

<script>
import {NcContent} from "@nextcloud/vue"
import {NcActionButton} from "@nextcloud/vue"
import {NcAppContent} from "@nextcloud/vue"
import {NcAppNavigation} from "@nextcloud/vue"
import {NcAppNavigationItem} from "@nextcloud/vue"
import {NcAppNavigationNew} from "@nextcloud/vue"
import {NcEmptyContent} from "@nextcloud/vue"
import {NcActions} from "@nextcloud/vue"
import Pencil from 'vue-material-design-icons/Pencil'
import Delete from 'vue-material-design-icons/Delete'

import '@nextcloud/dialogs/styles/toast.scss'
import { generateUrl } from '@nextcloud/router'
import { showError, showSuccess, showInfo } from '@nextcloud/dialogs'
import axios from '@nextcloud/axios'

export default {
	name: 'App',
	components: {
    NcContent,
		NcActionButton,
		NcAppContent,
		NcAppNavigation,
		NcAppNavigationItem,
		NcAppNavigationNew,
    NcEmptyContent,
    NcActions,
    Pencil,
    Delete
	},
	data() {
		return {
			profiles: [],
			currentProfileId: null,
			updating: false,
			loading: true,
		}
	},
	computed: {
		/**
		 * Return the currently selected profile object
		 * @returns {Object|null}
		 */
		currentProfile() {
			if (this.currentProfileId === null) {
				return null
			}
			return this.profiles.find((profile) => profile.id === this.currentProfileId)
		},

		/**
		 * Returns true if a profile is selected and its title is not empty
		 * @returns {Boolean}
		 */
		savePossible() {
			return this.currentProfile
          && this.currentProfile.name !== ''
          && this.currentProfile.age !== ''
          && this.currentProfile.contraceptive !== ''
		},
	},
	/**
	 * Fetch list of profiles when the component is loaded
	 */
	async mounted() {
		try {
			const response = await axios.get(generateUrl('/apps/period/profiles'))
			this.profiles = response.data
		} catch (e) {
			console.error(e)
			showError(t('period', 'Could not fetch profiles'))
		}
		this.loading = false
	},

	methods: {
		/**
		 * Create a new profile and focus the profile content field automatically
		 * @param {Object} profile Profile object
		 */
		openProfile(profile) {
			if (this.updating) {
				return
			}
			this.currentProfileId = profile.id
			this.$nextTick(() => {
				this.$refs.name.focus()
			})
		},
		/**
		 * Action triggered when clicking the save button
		 * create a new profile or save
		 */
		saveProfile() {
			if (this.currentProfileId === -1) {
				this.createProfile(this.currentProfile)
			} else {
				this.updateProfile(this.currentProfile)
			}
		},
		/**
		 * Create a new profile and focus the profile contraceptive field automatically
		 * The note is not yet saved, therefore an id of -1 is used until it
		 * has been persisted in the backend
		 */
		newProfile() {
			if (this.currentProfileId !== -1) {
				this.currentProfileId = -1
				this.profiles.push({
					id: -1,
          name: '',
					age: '',
					contraceptive: '',
				})
				this.$nextTick(() => {
					this.$refs.name.focus()
				})
			}
		},
		/**
		 * Abort creating a new profile
		 */
		cancelNewProfile() {
			this.profiles.splice(this.profiles.findIndex((profile) => profile.id === -1), 1)
			this.currentProfileId = null
		},
		/**
		 * Create a new profile by sending the information to the server
		 * @param {Object} profile Profile object
		 */
		async createProfile(profile) {
			this.updating = true
			try {
				const response = await axios.post(generateUrl('/apps/period/profiles'), profile)
				const index = this.profiles.findIndex((match) => match.id === this.currentProfileId)
				this.$set(this.profiles, index, response.data)
				this.currentProfileId = response.data.id
			} catch (e) {
				console.error(e)
				showError(t('period', 'Could not create the profile'))
			}
			this.updating = false
		},
		/**
		 * Update an existing profile on the server
		 * @param {Object} profile Profile object
		 */
		async updateProfile(profile) {
			this.updating = true
			try {
				await axios.put(generateUrl(`/apps/period/profiles/${profile.id}`), profile)
			} catch (e) {
				console.error(e)
				showError(t('period', 'Could not update the profile'))
			}
			this.updating = false
		},
		/**
		 * Delete a profile, remove it from the frontend and show a hint
		 * @param {Object} profile Profile object
		 */
		async deleteProfile(profile) {
			try {
        showInfo(t('period', 'Deleting profile ' + profile.name))
        showInfo(t('period', 'Current profile id ' + this.currentProfileId))
				await axios.delete(generateUrl(`/apps/period/profiles/${profile.id}`))
				this.profiles.splice(this.profiles.indexOf(profile), 1)
				if (this.currentProfileId === profile.id) {
					this.currentProfileId = null
				}
				showSuccess(t('period', 'Profile deleted'))
			} catch (e) {
        console.error(profile)
				console.error(e)
				showError(t('period', 'Could not delete the profile'))
			}
		},

    isEmpty() {
      return this.profiles.length === 0
    },
	},
}
</script>
<style scoped>
	#app-content > div {
		width: 100%;
		height: 100%;
		padding: 20px;
		display: flex;
		flex-direction: column;
		flex-grow: 1;
	}

  .profile-content {
    margin: 40px 0 0 40px;
  }

  .profile-content h2 {
    margin-top: 30px;
  }

  .profile-content div {
    width: 100%;
    margin-top: 30px;
  }

  .profile-content div.form {
    width: 90%;
    margin: 0 auto;
  }

	input[type='text'] {
		width: 100%;
	}

	textarea {
		flex-grow: 1;
		width: 100%;
	}
</style>
