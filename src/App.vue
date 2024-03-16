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
          :text="t('period data', 'Add data for today')"
          :disabled="false"
          button-id="add-data-button"
          button-class="icon-add"
          @click="newCalendar" />
        <ul>
          <NcAppNavigationItem v-for="calendar in calendars"
            :key="calendar.id"
            :name="calendar.date ? calendar.date : t('period data', 'Add data for today')"
            :class="{active: currentCalendarId === calendar.id}"
            @click="openCalendar(calendar)">
            <template #icon>
              <Pencil :size="20" />
            </template>
            <template #actions>
              <NcActionButton @click="deleteCalendar(calendar)">
                <template #icon>
                  <Delete :size="20" />
                </template>
                {{ t('period data', 'Delete data') }}
              </NcActionButton>
            </template>
          </NcAppNavigationItem>
        </ul>
      </NcAppNavigation>
      <NcAppContent>
        <div v-if="currentCalendar" class="calendar-content">
          <h2>{{t('period data', 'Data for today')}}: {{ currentCalendar.date }}</h2>
          <div class="form">
            <label>How do you feel today?</label>
            <input ref="feeling"
                   v-model="currentCalendar.feeling"
                   type="text"
                   :disabled="updating"/>
            <label>Note</label>
            <input ref="note"
              v-model="currentCalendar.note"
              type="text"
              :disabled="updating">
            <label>Symptoms</label>
            <input ref="symptomId"
                   v-model="currentCalendar.symptomId"
                   type="text"
                   :disabled="updating"/>
            <label>Contraceptive</label>
            <input ref="contraceptiveId"
                   v-model="currentCalendar.contraceptiveId"
                   type="text"
                   :disabled="updating"/>
            <input type="button"
              class="primary"
              :value="t('period data', 'Save')"
              :disabled="updating || !savePossible"
              @click="saveCalendar">
          </div>
        </div\=]-[po
        <NcEmptyContent v-if="isEmpty" key="empty">
          <template #icon>
            <Plus :size="20" />
          </template>
          <template #title>
            {{ t('period data', 'Add data for today to get started') }}
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
			calendars: [],
			currentCalendarId: null,
			updating: false,
			loading: true,
		}
	},
	computed: {
		/**
		 * Return the currently selected calendar object
		 * @returns {Object|null}
		 */
		currentCalendar() {
			if (this.currentCalendarId === null) {
				return null
			}
			return this.calendars.find((calendar) => calendar.id === this.currentCalendarId)
		},

		/**
		 * Returns true if a calendar is selected and its title is not empty
		 * @returns {Boolean}
		 */
		savePossible() {
			return this.currentCalendarId
          && this.currentCalendar.feeling !== ''
          && this.currentCalendar.note !== ''
          && this.currentCalendar.symptomId !== ''
          && this.currentCalendar.contraceptiveId !== ''
		},
	},
	/**
	 * Fetch list of calendars when the component is loaded
	 */
	async mounted() {
		try {
			const response = await axios.get(generateUrl('/apps/period/calendars'))
			this.calendars = response.data
		} catch (e) {
			console.error(e)
			showError(t('period', 'Could not fetch data'))
		}
		this.loading = false
	},

	methods: {
		/**
		 * Create a new calendar and focus the calendar content field automatically
		 * @param {Object} calendar Calendar object
		 */
		openCalendar(calendar) {
			if (this.updating) {
				return
			}
			this.currentCalendarId = calendar.id
			this.$nextTick(() => {
				this.$refs.feeling.focus()
			})
		},
		/**
		 * Action triggered when clicking the save button
		 * create a new calendar or save
		 */
		saveCalendar() {
			if (this.currentCalendarId === -1) {
				this.createCalendar(this.currentCalendar)
			} else {
				this.updateCalendar(this.currentCalendar)
			}
		},
		/**
		 * Create a new calendar and focus the feeling field automatically
		 * The note is not yet saved, therefore an id of -1 is used until it
		 * has been persisted in the backend
		 */
		newCalendar() {
			if (this.currentCalendarId !== -1) {
				this.currentCalendarId = -1
				this.calendars.push({
					id: -1,
          feeling: '',
          date: '',
					note: '',
          symptomId: '',
					contraceptiveId: '',
				})
				this.$nextTick(() => {
					this.$refs.feeling.focus()
				})
			}
		},
		/**
		 * Abort creating a new calendar
		 */
		cancelNewCalendar() {
			this.calendars.splice(this.calendars.findIndex((calendar) => calendar.id === -1), 1)
			this.currentCalendarId = null
		},
		/**
		 * Create a new calendar by sending the information to the server
		 * @param {Object} calendar Calendar object
		 */
		async createCalendar(calendar) {
			this.updating = true
			try {
				const response = await axios.post(generateUrl('/apps/period/calendars'), calendar)
				const index = this.calendars.findIndex((match) => match.id === this.currentCalendarId)
				this.$set(this.calendars, index, response.data)
				this.currentCalendarId = response.data.id
			} catch (e) {
				console.error(e)
				showError(t('period', 'Could not create the data'))
			}
			this.updating = false
		},
		/**
		 * Update an existing calendar on the server
		 * @param {Object} calendar Calendar object
		 */
		async updateCalendar(calendar) {
			this.updating = true
			try {
				await axios.put(generateUrl(`/apps/period/calendars/${calendar.id}`), calendar)
			} catch (e) {
				console.error(e)
				showError(t('period data', 'Could not update the date'))
			}
			this.updating = false
		},
		/**
		 * Delete a calendar, remove it from the frontend and show a hint
		 * @param {Object} calendar Calendar object
		 */
		async deleteCalendar(calendar) {
			try {
        showInfo(t('period', 'Deleting calendar ' + calendar.feeling))
        showInfo(t('period', 'Current calendar id ' + this.currentCalendarId))
				await axios.delete(generateUrl(`/apps/period/calendars/${calendar.id}`))
				this.calendars.splice(this.calendars.indexOf(calendar), 1)
				if (this.currentCalendarId === calendar.id) {
					this.currentCalendarId = null
				}
				showSuccess(t('period', 'Data deleted'))
			} catch (e) {
        console.error(calendar)
				console.error(e)
				showError(t('period', 'Could not delete the data'))
			}
		},

    isEmpty() {
      return this.calendars.length === 0
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

  .calendar-content {
    margin: 40px 0 0 40px;
  }

  .calendar-content h2 {
    margin-top: 30px;
  }

  .calendar-content div {
    width: 100%;
    margin-top: 30px;
  }

  .calendar-content div.form {
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
