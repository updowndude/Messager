<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="post.aspx.cs" MasterPageFile="~/layout.Master" Inherits="Messenger.pages.post" %>

<asp:Content ID="postsTitle" ContentPlaceHolderID="title" runat="server">
    <link rel="stylesheet" type="text/css" href="../public/dist/myStyle.css" />
   <!-- links to rescoures -->
   <link rel="icon", type="image/x-icon", href="../public/images/favicon.ico" />
   <link rel="shortcut icon", type="image/x-icon", href="../public/images/favicon.ico" />
   <title>Groups</title>
</asp:Content>

<asp:Content ID="postsPage" ContentPlaceHolderID="body" runat="server">
    <form id="postsForm" runat="server">
        <nav class="navbar navbar-light bg-faded">
         <ul class="nav navbar-nav">
            <li class="nav-item active">
                <asp:LinkButton ID="btnHome" CssClass="btn btn-primary" runat="server" OnClick="btnHome_Click">
                    <span class="glyphicon glyphicon-home"></span>
                </asp:LinkButton>
            </li>
         </ul>
      </nav>
        <section>
            <article>

            </article>
        </section>
    </form>
<script src="../public/dist/my-com.js" type="text/javascript"></script>
</asp:Content>
