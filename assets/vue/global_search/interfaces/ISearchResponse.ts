import {IEntitySearchResponse} from "@/assets/vue/global_search/interfaces/IEntitySearchResponse";
import {IRouteSearchResponse} from "@/assets/vue/global_search/interfaces/IRouteSearchResponse";

export interface ISearchResponse {
    redirectPage: string,
    content: IEntitySearchResponse[] | IRouteSearchResponse[]
}