import { defineStore, acceptHMRUpdate } from 'pinia'
import axios from 'axios'
import {ISearchResponse} from "@/assets/vue/global_search/interfaces/ISearchResponse";

export const useSearchStore = defineStore('searchStore', {
    state: () => {
        return {
            searchQuery: "" as string,
            searchMode: "SIMPLE" as "ADVANCED" | "SIMPLE",
            searchScopeSelect: "/e",
            searchEntityName: "",
            searchEntityFieldName: "",
            result: {} as ISearchResponse
        }
    },
    actions: {
        search(query: string) {
            if (query.length > 0) axios.get(`/api/v1/search/r?q=${query}`).then(r => {
                this.result = r.data
                console.log(r.data)
            })
        },
        toggleSearchMode: function () {
            if (this.searchMode === 'SIMPLE') this.searchMode = 'ADVANCED'
            else this.searchMode = 'SIMPLE'
        },
        searchHandler() {
            if (this.searchMode === 'ADVANCED') {
                let query = "";
                if (this.searchScopeSelect === '/e') {
                    query += this.searchScopeSelect + " "
                    if (this.searchEntityName.length > 2) {
                        query += this.searchEntityName;
                        if (this.searchEntityFieldName.length > 0) {
                            query += ":" + this.searchEntityFieldName + " " + this.searchQuery
                        } else {
                            query += " " + this.searchQuery
                        }
                    } else {
                        query += " " + this.searchQuery
                    }
                } else if (this.searchScopeSelect === '/r') {
                    query += this.searchScopeSelect + " " + this.searchQuery
                }
                this.search(query);
            } else {
                this.search(this.searchQuery);
            }
        }
    }
})

// @ts-ignore
if (import.meta.hot) {
    // @ts-ignore
    import.meta.hot.accept(acceptHMRUpdate(useAuth, import.meta.hot))
}