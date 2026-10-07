import { defineStore } from "pinia"
import { settings } from "../../settings"
import { getSiteInfo, getMenu } from "../../api/wp/getEssentials"

// Filled by WordPress on page load
export const useSiteStore = defineStore('wp-site', {
    state: () => ({
        title: settings.SITE.title || '',
        description: settings.SITE.description || '',
        language: settings.SITE.language || 'en-US',
        logo: settings.SITE.logo || null,
        menus: { ...settings.MENUS },
    }),
    actions: {
        menu(location) {
            return this.menus[location] || []
        },
        async refresh() {
            const site = await getSiteInfo()

            this.title = site.title
            this.description = site.description
            this.language = site.language
            this.logo = site.logo
        },
        async refreshMenu(location) {
            this.menus[location] = await getMenu(location)
        },
    }
})
