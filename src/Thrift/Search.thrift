namespace java com.skletter.Search
namespace php Skletter.Model.RemoteService.Search
include "SearchDTO.thrift"

service Search
{
    list<SearchDTO.SearchProfile> suggest(1: string key);
    list<SearchDTO.SearchProfile> search(1: string key);
    void registerIndex(1: SearchDTO.SearchProfile profile);
}