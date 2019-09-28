namespace java com.skletter.Romeo.Exception
namespace php Skletter.Model.RemoteService.Exception

exception NonExistentUser
{
  1: string	message
}

exception PasswordMismatch
{
    1: string message
}


exception UserExists
{
  1: string field
  2: string error
}

exception ValidationError
{
    1: map<string, string> errors
}

exception NullDTOException
{
    1: string error
}