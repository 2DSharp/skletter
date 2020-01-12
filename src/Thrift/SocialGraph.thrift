namespace java com.skletter.SocialGraph.Remote
namespace php Skletter.Model.RemoteService.SocialGraph


service SocialGraph
{
    list<i32> getFollowersList(1: i32 userId);
    list<i32> getFollowingList(1: i32 userId);
}